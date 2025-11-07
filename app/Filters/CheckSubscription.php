<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\SubscriptionModel;

class CheckSubscription implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('auth');
        $userId = user_id();
        if (!$userId) {
            return redirect()->to('/login');
        }

        $subscriptionModel = new SubscriptionModel();

        // Ambil langganan terbaru user
        $latest = $subscriptionModel
            ->where('user_id', $userId)
            ->orderBy('end_date', 'DESC')
            ->first();

        // Belum pernah langganan
        if (!$latest) {
            return redirect()->to('/user/subscription')
                ->with('error', 'Kamu belum berlangganan. Silakan aktifkan langganan terlebih dahulu.');
        }

        $now = time();
        $end = strtotime($latest['end_date'] ?? '1970-01-01');

        // ✅ AUTO UPDATE: kalau end_date sudah lewat dan masih bukan expired → ubah jadi expired
        if ($end < $now && $latest['status'] !== 'expired') {
            $subscriptionModel->update($latest['id'], ['status' => 'expired']);
        }

        // Kalau status sudah expired setelah dicek → blokir
        if ($latest['status'] === 'expired' || $end < $now) {
            return redirect()->to('/user/subscription')
                ->with('error', 'Langganan kamu sudah berakhir. Silakan lakukan pembelian ulang.');
        }

        // ✅ ACTIVE dan belum expired → boleh akses
        if ($latest['status'] === 'active') {
            return;
        }

        // ✅ PENDING tapi masih punya sisa dari langganan sebelumnya (perpanjang / upgrade)
        if ($latest['status'] === 'pending') {
            $priorActive = $subscriptionModel
                ->where('user_id', $userId)
                ->where('status', 'active')
                ->where('end_date >', date('Y-m-d'))
                ->first();

            if ($priorActive) {
                return; // masih boleh akses
            }

            // Pending tapi belum pernah punya sisa (pembelian pertama)
            return redirect()->to('/user/subscription')
                ->with('error', 'Pembelian pertama kamu sedang menunggu konfirmasi admin. Akses aktif setelah disetujui.');
        }

        // Kalau kondisi lain, arahkan balik
        return redirect()->to('/user/subscription')
            ->with('error', 'Langganan kamu belum aktif.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }
}

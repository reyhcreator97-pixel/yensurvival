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
        $subscription = $subscriptionModel
            ->where('user_id', $userId)
            ->orderBy('end_date', 'DESC')
            ->first();

        // kalau belum pernah langganan
        if (!$subscription) {
            return redirect()->to('/user/subscription')
                ->with('error', 'Kamu belum berlangganan. Silakan aktifkan langganan terlebih dahulu.');
        }

        // ✅ FIX 1: user masih bisa akses jika end_date > hari ini (walaupun status pending)
        if (strtotime($subscription['end_date']) < time()) {
            return redirect()->to('/user/subscription')
                ->with('error', 'Langganan kamu sudah berakhir. Silakan perpanjang.');
        }

        // ✅ FIX 2: izinkan akses jika status = active atau pending tapi masih ada sisa hari
        if (!in_array($subscription['status'], ['active', 'pending'])) {
            return redirect()->to('/user/subscription')
                ->with('error', 'Langganan kamu belum aktif atau sudah berakhir.');
        }

        // kalau masih aktif atau pending (dan belum expired), lanjut
        return;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu apa-apa di sini
    }
}

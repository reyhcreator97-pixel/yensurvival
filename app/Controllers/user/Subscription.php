<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Controllers\KursDcom;
use App\Models\SubscriptionModel;
use App\Models\TransaksiModel;
use App\Models\CouponModel;
use Config\Database;

class Subscription extends BaseController
{
    protected $db;
    protected $subscriptionModel;
    protected $transaksiModel;

    public function __construct()
    {
        helper(['auth']);
        $this->db = Database::connect();
        $this->subscriptionModel = new SubscriptionModel();
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $userId = user_id();

        // Ambil data subscription user
        $subscription = $this->subscriptionModel
            ->where('user_id', $userId)
            ->orderBy('end_date', 'DESC')
            ->first();

        // 🔥 Ambil transaksi subscription saja + pagination
        $perPage = 5;
        $billings = $this->transaksiModel
            ->where('user_id', $userId)
            ->where('kategori', 'subscription')
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, 'transaksi');

        $pager = $this->transaksiModel->pager;

        // Ambil harga plan dari tabel settings
        $settings = $this->db->table('settings')->get()->getRow();
        $priceMonthly = $settings->price_monthly ?? 0;
        $priceYearly  = $settings->price_yearly ?? 0;
        $adminWa      = $settings->contact_whatsapp ?? '628123456789';

        $data = [
            'title'        => 'Subscription Plan',
            'subscription' => $subscription,
            'billings'     => $billings,
            'pager'        => $pager,
            'priceMonthly' => $priceMonthly,
            'priceYearly'  => $priceYearly,
            'adminWa'      => $adminWa,
        ];

        return view('user/subscription', $data);
    }

    public function buy($plan)
    {
        $userId = user_id();
        $settings = $this->db->table('settings')->get()->getRow();
        $couponModel = new CouponModel();

        // === Ambil harga awal plan ===
        $priceYen = ($plan === 'monthly') ? (float)$settings->price_monthly : (float)$settings->price_yearly;
        $duration = ($plan === 'monthly') ? 30 : 365;

        // === Ambil kurs real-time ===
        $kursCtrl = new KursDcom();
        $kurs = $kursCtrl->getKurs();
        $kurs = $kurs > 0 ? $kurs : 110;
        $priceIDR = $priceYen * $kurs;

        // === Ambil kode kupon dari GET / POST (fix utama) ===
        $couponCode = $this->request->getGet('coupon') ?? $this->request->getPost('applied_coupon');
        $discount = 0;
        $discountLabel = '';

        // === Hitung diskon kupon kalau ada ===
        if ($couponCode) {
            $coupon = $couponModel
                ->where('kode', $couponCode)
                ->where('status', 'active')
                ->where('berlaku_mulai <=', date('Y-m-d'))
                ->where('berlaku_sampai >=', date('Y-m-d'))
                ->first();

            if ($coupon) {
                if ($coupon['jenis'] === 'percent') {
                    $discount = ($priceYen * $coupon['nilai'] / 100);
                    $discountLabel = "{$coupon['nilai']}%";
                } else {
                    $discount = (float)$coupon['nilai'];
                    $discountLabel = '¥ ' . number_format($coupon['nilai'], 0, ',', '.');
                }

                // Hitung ulang harga
                $priceYen = max(0, $priceYen - $discount);
                $priceIDR = max(0, $priceYen * $kurs);

                // Update penggunaan kupon
                $couponModel->set('used_count', 'used_count + 1', false)
                    ->where('id', $coupon['id'])
                    ->update();
            }
        }

        // === Tanggal dan durasi ===
        $startDate = date('Y-m-d');
        $endDate   = date('Y-m-d', strtotime("+{$duration} days"));
        $today     = date('Y-m-d');

        // 🔥 Cek subscription aktif user
        $activeSub = $this->subscriptionModel
            ->where('user_id', $userId)
            ->whereIn('status', ['active', 'pending'])
            ->orderBy('end_date', 'DESC')
            ->first();

        // Jika belum punya langganan sama sekali
        if (!$activeSub) {
            $this->subscriptionModel->insert([
                'user_id'    => $userId,
                'plan_type'  => $plan,
                'price'      => $priceYen,
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'status'     => 'pending'
            ]);
        } else {
            $currentPlan = $activeSub['plan_type'];
            $currentEnd  = $activeSub['end_date'];

            // 🔹 Jika masih aktif dan beli plan yang sama → perpanjang
            if ($currentPlan === $plan) {
                $base = (strtotime($currentEnd) > strtotime($today)) ? $currentEnd : $today;

                $newEnd = ($plan === 'monthly')
                    ? date('Y-m-d', strtotime($base . ' +30 days'))
                    : date('Y-m-d', strtotime($base . ' +365 days'));

                $this->subscriptionModel->update($activeSub['id'], [
                    'end_date' => $newEnd,
                ]);
            }

            // 🔹 Jika upgrade (monthly → yearly)
            elseif ($currentPlan === 'monthly' && $plan === 'yearly') {
                $base = (strtotime($currentEnd) > strtotime($today)) ? $currentEnd : $today;
                $newEnd = date('Y-m-d', strtotime($base . ' +365 days'));

                $this->subscriptionModel->update($activeSub['id'], [
                    'plan_type' => 'yearly',
                    'end_date'  => $newEnd,
                ]);
            }

            // 🔹 Jika langganan lama sudah expired → buat baru
            elseif ($activeSub['status'] === 'expired' || strtotime($currentEnd) < strtotime($today)) {
                $this->subscriptionModel->insert([
                    'user_id'    => $userId,
                    'plan_type'  => $plan,
                    'price'      => $priceYen,
                    'start_date' => $startDate,
                    'end_date'   => $endDate,
                    'status'     => 'pending'
                ]);
            }
        }

        // === Catat transaksi (harga final + kupon kalau ada) ===
        $this->transaksiModel->insert([
            'user_id'   => $userId,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'out',
            'kategori'  => 'subscription',
            'deskripsi' => ucfirst($plan) . ' Plan Subscription' . ($couponCode ? " (Kupon: {$couponCode})" : ''),
            'jumlah'    => $priceYen,
            'status'    => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/user/subscription')
            ->with('message', 'Pembelian Subscription Berhasil.' . ($couponCode ? " Dengan Diskon : {$discountLabel}" : ''));
    }

    public function checkout($plan)
    {
        $userId = user_id();
        $settings = $this->db->table('settings')->get()->getRow();

        if (!$settings) {
            return redirect()->to('/user/subscription')->with('error', 'Konfigurasi sistem belum tersedia.');
        }

        // Tentukan plan
        $price = ($plan == 'monthly') ? $settings->price_monthly : $settings->price_yearly;
        $duration = ($plan == 'monthly') ? 30 : 365;

        // 🔹 Ambil kurs real-time dari controller KursDcom
        $kursController = new \App\Controllers\KursDcom();
        $kurs = $kursController->getKurs();
        if (!$kurs || $kurs == 0) {
            $kurs = 110;
        }

        $data = [
            'title'     => 'Checkout Subscription',
            'plan'      => ucfirst($plan),
            'price'     => $price,
            'duration'  => $duration,
            'currency'  => $settings->currency,
            'adminWa'   => $settings->contact_whatsapp,
            'kurs'      => $kurs,
        ];

        return view('user/checkout', $data);
    }
}

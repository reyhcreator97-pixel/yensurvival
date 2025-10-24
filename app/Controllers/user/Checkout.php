<?php
namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\TransaksiModel;
use App\Models\SubscriptionModel;

class Checkout extends BaseController
{
    protected $settings;
    protected $transaksiModel;
    protected $subscriptionModel;

    public function __construct()
    {
        $this->settings = new SettingModel();
        $this->transaksiModel = new TransaksiModel();
        $this->subscriptionModel = new SubscriptionModel();
    }

    public function index($plan = null)
    {
        if (!$plan || !in_array($plan, ['monthly', 'yearly'])) {
            return redirect()->to('/user/subscription')->with('error', 'Paket tidak valid.');
        }
    
        $config = $this->settings->first();
        $price  = ($plan === 'monthly') ? $config['price_monthly'] : $config['price_yearly'];
        $currency = $config['currency'] ?? '¥';
        $wa = $config['contact_whatsapp'] ?? '';
    
        // Tambahkan data rekening
        $rekeningJapan = [
            'bank' => 'MUFG Japan',
            'no'   => '123-456-7890',
            'nama' => 'Rey Creator'
        ];
    
        $rekeningIndo = [
            'bank' => 'BCA Indonesia',
            'no'   => '987-654-3210',
            'nama' => 'Rey Creator'
        ];
    
        return view('user/checkout', [
            'title'    => 'Checkout Subscription',
            'plan'     => $plan,
            'price'    => $price,
            'currency' => $currency,
            'wa'       => $wa,
            'rekeningJapan' => $rekeningJapan,
            'rekeningIndo'  => $rekeningIndo,
        ]);
    }

    public function confirm()
    {
        $plan = $this->request->getPost('plan');
        if (!$plan) {
            return redirect()->to('/user/subscription')->with('error', 'Data tidak valid.');
        }

        $config = $this->settings->first();
        $price  = ($plan === 'monthly') ? $config['price_monthly'] : $config['price_yearly'];
        $duration = ($plan === 'monthly') ? 30 : 365;

        $userId = user_id();
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$duration} days"));

        // Simpan ke tabel subscription (pending)
        $this->subscriptionModel->insert([
            'user_id'    => $userId,
            'plan'       => $plan,
            'price'      => $price,
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'status'     => 'pending'
        ]);

        // Catat transaksi subscription
        $this->transaksiModel->insert([
            'user_id'     => $userId,
            'kategori'    => 'subscription',
            'deskripsi'   => ucfirst($plan).' Plan Subscription',
            'jumlah'      => $price,
            'status'      => 'pending'
        ]);

        return redirect()->to('/user/subscription')
            ->with('message', 'Pesanan berhasil dibuat. Silakan konfirmasi pembayaran via WhatsApp.');
    }
}
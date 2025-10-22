<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\SubscriptionModel;
use App\Models\TransaksiModel;
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
    
        $price = ($plan === 'monthly') ? $settings->price_monthly : $settings->price_yearly;
        $duration = ($plan === 'monthly') ? 30 : 365;
    
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+{$duration} days"));
    
        // Simpan ke tabel subscription
        $this->subscriptionModel->insert([
            'user_id'    => $userId,
            'plan'       => $plan,
            'price'      => $price,
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'status'     => 'pending'
        ]);
    
        // Catat juga ke tabel transaksi
        $this->transaksiModel->insert([
            'user_id'   => $userId,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'out', // pengeluaran
            'kategori'  => 'subscription',
            'deskripsi' => ucfirst($plan) . ' Plan Subscription',
            'jumlah'    => $price,
            'status'    => 'pending', // baru ditambahkan ✅
            'created_at'=> date('Y-m-d H:i:s'),
            'updated_at'=> date('Y-m-d H:i:s')
        ]);
    
        return redirect()->to('/user/subscription')->with('message', 'Pemesanan berhasil dibuat. Silakan konfirmasi pembayaran melalui WhatsApp.');
    }
}
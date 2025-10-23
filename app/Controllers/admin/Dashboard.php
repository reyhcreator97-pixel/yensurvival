<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UsersModel;
use App\Models\SubscriptionModel;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    protected $users;
    protected $subs;
    protected $trx;

    public function __construct()
    {
        $this->users = new UsersModel();
        $this->subs  = new SubscriptionModel();
        $this->trx   = new TransaksiModel();
    }

    public function index()
    {
        // log_activity('Login', 'Admin login ke dashboard');
        // ✅ Tambah logika agar semua variable pasti dikirim
        $data = [
            'title' => 'Admin Dashboard',
            'total_users' => $this->users->countAllResults(),
            'active_subscriptions' => $this->subs->where('status', 'active')->countAllResults(),
            'expired_subscriptions' => $this->subs->where('status', 'expired')->countAllResults(),
            'total_income' => 0,
            'latest_subs' => []
        ];

        // Hitung income
        $income = $this->trx->selectSum('jumlah')->where('jenis', 'out')->where('kategori','subscription')->get()->getRow('jumlah');
        $data['totalIncome'] = $income ?? 0;

        // Ambil data 5 subscription terakhir
        $data['latest_subs'] = $this->subs
            ->select('subscriptions.*, users.username, users.email')
            ->join('users', 'users.id = subscriptions.user_id', 'left')
            ->orderBy('subscriptions.created_at', 'DESC')
            ->limit(5)
            ->findAll();

        // ✅ Pasti kirim semua data ke view
        return view('admin/dashboard', $data);
    }
}
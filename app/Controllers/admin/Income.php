<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\SubscriptionModel;
use App\Models\UsersModel;

class Income extends BaseController
{
    protected $transaksiModel;
    protected $subscriptionModel;
    protected $userModel;

    public function __construct()
    {
        $this->transaksiModel    = new TransaksiModel();
        $this->subscriptionModel = new SubscriptionModel();
        $this->userModel         = new UsersModel();
    }

    public function index()
    {
        $month = $this->request->getGet('month');
        $year  = $this->request->getGet('year');

        $builder = $this->transaksiModel
            ->select('transaksi.*, users.username, users.email')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->where('transaksi.kategori', 'subscription');

        // Filter by month & year (jika dipilih)
        if ($month && $year) {
            $builder->where('MONTH(transaksi.created_at)', $month)
                    ->where('YEAR(transaksi.created_at)', $year);
        } elseif ($year) {
            $builder->where('YEAR(transaksi.created_at)', $year);
        }

        // Pagination
        $perPage = 10;
        $incomes = $builder->orderBy('transaksi.created_at', 'DESC')->paginate($perPage, 'incomes');
        $pager   = $this->transaksiModel->pager;

        // Hitung total income aktif
        $totalIncome = $this->transaksiModel
            ->where('kategori', 'subscription')
            ->where('status', 'active')
            ->selectSum('jumlah')
            ->get()
            ->getRow()
            ->jumlah ?? 0;

        $data = [
            'title'       => 'Income Management',
            'incomes'     => $incomes,
            'pager'       => $pager,
            'totalIncome' => $totalIncome,
            'month'       => $month,
            'year'        => $year
        ];

        return view('admin/income', $data);
    }

    public function approve($id)
    {
        $db = \Config\Database::connect();
    
        // 🔹 Ambil data transaksi
        $transaksi = $this->transaksiModel->find($id);
        if (!$transaksi) {
            return redirect()->back()->with('error', 'Transaksi tidak ditemukan.');
        }
    
        if ($transaksi['kategori'] !== 'subscription') {
            return redirect()->back()->with('error', 'Transaksi ini bukan transaksi subscription.');
        }
    
        // 🔹 Update transaksi pakai query builder langsung (anti bug)
        $db->table('transaksi')
           ->where('id', $id)
           ->update(['status' => 'active']);
    
        // 🔹 Ambil subscription terakhir milik user
        $sub = $db->table('subscriptions')
                  ->where('user_id', $transaksi['user_id'])
                  ->orderBy('id', 'DESC')
                  ->get()
                  ->getRowArray();
    
        // 🔹 Tentukan plan_type dan durasi
        $planType = (stripos($transaksi['deskripsi'], 'year') !== false) ? 'yearly' : 'monthly';
        $duration = ($planType === 'yearly') ? 365 : 30;
    
        if ($sub) {
            // 🔹 Update subscription terakhir user
            $db->table('subscriptions')
               ->where('id', $sub['id'])
               ->update([
                   'status' => 'active',
                   'plan_type' => $planType,
                   'end_date' => date('Y-m-d', strtotime("+{$duration} days")),
                   'updated_at' => date('Y-m-d H:i:s')
               ]);
        } else {
            // 🔹 Kalau belum ada subscription, buat baru
            $db->table('subscriptions')->insert([
                'user_id'    => $transaksi['user_id'],
                'plan_type'  => $planType,
                'price'      => $transaksi['jumlah'],
                'start_date' => date('Y-m-d'),
                'end_date'   => date('Y-m-d', strtotime("+{$duration} days")),
                'status'     => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]);
        }
    
        return redirect()->to('/admin/income')->with('message', 'Transaksi berhasil di-ACC dan subscription user aktif.');
    }

}
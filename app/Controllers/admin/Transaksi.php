<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\UsersModel;

class Transaksi extends BaseController
{
    protected $trx;
    protected $users;

    public function __construct()
    {
        $this->trx   = new TransaksiModel();
        $this->users = new UsersModel();
    }

    public function index()
    {
        $mode  = $this->request->getGet('mode')  ?? 'monthly';  // daily|monthly|yearly
        $date  = $this->request->getGet('date')  ?? date('Y-m-d');
        $month = $this->request->getGet('month') ?? date('Y-m');
        $year  = $this->request->getGet('year')  ?? date('Y');

        // === Filter builder ===
        $applyFilter = function($builder) use ($mode, $date, $month, $year) {
            if ($mode === 'daily') {
                $builder->where('tanggal', $date);
            } elseif ($mode === 'monthly') {
                [$y, $m] = explode('-', $month);
                $builder->where('YEAR(tanggal)', $y)
                        ->where('MONTH(tanggal)', $m);
            } else {
                $builder->where('YEAR(tanggal)', $year);
            }
            return $builder;
        };

        // === Daftar transaksi (gabung dengan user) ===
        $builder = $this->trx
            ->select('transaksi.*, users.username, users.email')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->orderBy('transaksi.tanggal', 'DESC');

        $applyFilter($builder);
        $list = $builder->findAll();

        // === Hitung total in/out ===
        $builderIn = $this->trx->selectSum('jumlah')->where('jenis', 'in');
        $applyFilter($builderIn);
        $totalIn = (float)($builderIn->get()->getRow('jumlah') ?? 0);

        $builderOut = $this->trx->selectSum('jumlah')->where('jenis', 'out');
        $applyFilter($builderOut);
        $totalOut = (float)($builderOut->get()->getRow('jumlah') ?? 0);

        $data = [
            'title'     => 'Transaksi Global',
            'mode'      => $mode,
            'date'      => $date,
            'month'     => $month,
            'year'      => $year,
            'list'      => $list,
            'totalIn'   => $totalIn,
            'totalOut'  => $totalOut,
            'saldo'     => $totalIn - $totalOut,
        ];

        return view('admin/transaksi', $data);
    }
}
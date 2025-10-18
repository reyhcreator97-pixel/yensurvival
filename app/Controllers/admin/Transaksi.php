<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\UsersModel;

class Transaksi extends BaseController
{
    protected $transaksiModel;
    protected $userModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
        $this->userModel = new UsersModel();
    }

    public function index()
    {
        $keyword   = $this->request->getGet('keyword');
        $kategori  = $this->request->getGet('kategori');
        $tanggal   = $this->request->getGet('tanggal');
        $perPage   = 10;
    
        $builder = $this->transaksiModel
            ->select('transaksi.*, COALESCE(users.username, "-") AS username, COALESCE(users.email, "-") AS email')
            ->join('users', 'users.id = transaksi.user_id', 'left');
    
        if ($keyword) {
            $builder->groupStart()
                ->like('users.username', $keyword)
                ->orLike('users.email', $keyword)
                ->orLike('transaksi.deskripsi', $keyword)
                ->groupEnd();
        }
    
        if ($kategori) {
            $builder->where('transaksi.kategori', $kategori);
        }
    
        if ($tanggal) {
            $builder->where('transaksi.tanggal', $tanggal);
        }
    
        // ⬇ Tambahin ini biar pagination start dari data terbaru
        $builder->orderBy('transaksi.tanggal', 'DESC')
                ->orderBy('transaksi.id', 'DESC');
    
        // Ambil data dengan pagination (tetap sama)
        $list = $builder->paginate($perPage, 'transaksi');
    
        // Urut ulang manual biar aman (kalau paginate kadang ngereset)
        usort($list, function($a, $b) {
            return strtotime($b['tanggal']) <=> strtotime($a['tanggal']);
        });
    
        $data = [
            'title'         => 'Daftar Transaksi',
            'list'          => $list,
            'pager'         => $this->transaksiModel->pager,
            'kategoriList'  => $this->transaksiModel->select('kategori')->distinct()->findAll(),
            'keyword'       => $keyword,
            'kategori'      => $kategori,
            'tanggal'       => $tanggal,
        ];
    
        return view('admin/transaksi', $data);
    }

    public function export()
    {
        $transaksi = $this->transaksiModel
            ->select('transaksi.*, users.username, users.email')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->orderBy('transaksi.tanggal', 'DESC')
            ->findAll();

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename="transaksi.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Tanggal', 'Username', 'Email', 'Kategori', 'Deskripsi', 'Jumlah', 'Jenis']);

        foreach ($transaksi as $r) {
            fputcsv($output, [
                $r['tanggal'], $r['username'], $r['email'],
                $r['kategori'], $r['deskripsi'], $r['jumlah'], $r['jenis']
            ]);
        }

        fclose($output);
        exit();
    }
}
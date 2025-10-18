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
        $keyword  = $this->request->getGet('keyword');
        $kategori = $this->request->getGet('kategori');
        $tanggal  = $this->request->getGet('tanggal');

        // Pagination setup
        $perPage = 10;
        $page    = $this->request->getVar('page_transaksi') ?? 1;

        $builder = $this->transaksiModel->select('transaksi.*, users.username, users.email')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->orderBy('transaksi.tanggal', 'DESC');

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

        $data = [
            'title'        => 'Daftar Transaksi',
            'list'         => $builder->paginate($perPage, 'transaksi'),
            'pager'        => $this->transaksiModel->pager,
            'kategoriList' => $this->transaksiModel->select('kategori')->distinct()->findAll(),
            'keyword'      => $keyword,
            'kategori'     => $kategori,
            'tanggal'      => $tanggal
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
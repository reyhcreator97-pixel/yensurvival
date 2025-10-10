<?php

namespace App\Controllers;

use App\Models\KekayaanItemModel;
use App\Models\TransaksiModel;

class Aset extends BaseController
{
    protected $items;
    protected $trx;

    public function __construct()
    {
        $this->items = new KekayaanItemModel();
        $this->trx   = new TransaksiModel();
        helper(['form']);
    }

    private function uid(): int
    {
        return (int) user_id();
    }

    // =========================
    // INDEX / LIST ASET
    // =========================
    public function index(): string
    {
        $uid    = $this->uid();
        $status = $this->request->getGet('status');

        // ambil data aset (termasuk dari kekayaan awal)
        $query = $this->items->where(['user_id' => $uid, 'kategori' => 'aset']);

        if ($status === 'aktif') {
            $query->groupStart()->where('saldo_terkini >', 0)->orWhere('saldo_terkini', null)->groupEnd();
        } elseif ($status === 'terjual') {
            $query->where('saldo_terkini <=', 0);
        }

        $list = $query->orderBy('id', 'DESC')->findAll();

        // total aset hanya hitung yang masih aktif
        $totalAset = 0;
        foreach ($list as $a) {
            $nilai = $a['saldo_terkini'] ?? $a['jumlah'];
            if ($nilai > 0) $totalAset += $nilai;
        }

        // hitung penyusutan
        foreach ($list as &$row) {
            $awal = (float) $row['jumlah'];
            $sekarang = (float) ($row['saldo_terkini'] ?? $awal);
            $row['penyusutan'] = max(0, $awal - $sekarang);
        }

        $data = [
            'title'      => 'Aset',
            'list'       => $list,
            'totalAset'  => $totalAset,
            'status'     => $status,
        ];

        return view('aset/index', $data);
    }

    // =========================
    // SIMPAN ASET BARU
    // =========================
    public function store()
    {
        $uid     = $this->uid();
        $nama    = trim((string)$this->request->getPost('nama'));
        $jumlah  = (float)$this->request->getPost('jumlah');
        $akun_id = (int)$this->request->getPost('akun_id');

        if ($nama === '' || $jumlah <= 0) {
            return redirect()->back()->with('error', 'Nama dan nilai aset wajib diisi.');
        }

        // simpan ke tabel kekayaan_item
        $this->items->insert([
            'user_id'       => $uid,
            'kategori'      => 'aset',
            'deskripsi'     => $nama,
            'jumlah'        => $jumlah,
            'saldo_terkini' => $jumlah,
        ]);
        $itemId = $this->items->getInsertID();

        // catat transaksi pembelian aset (uang keluar)
        if ($akun_id) {
            $this->trx->insert([
                'user_id'   => $uid,
                'tanggal'   => date('Y-m-d'),
                'jenis'     => 'out',
                'sumber_id' => $akun_id,
                'kategori'  => 'Aset',
                'deskripsi' => 'Pembelian aset: ' . $nama,
                'jumlah'    => $jumlah,
            ]);
        }

        return redirect()->to('/aset')->with('message', 'Aset baru berhasil ditambahkan.');
    }

    // =========================
    // UPDATE NILAI ASET (misal dijual / berubah harga)
    // =========================
    public function update($id)
    {
        $uid   = $this->uid();
        $row   = $this->items->find($id);
        if (!$row || $row['user_id'] != $uid || $row['kategori'] != 'aset') {
            return redirect()->back()->with('error', 'Aset tidak ditemukan.');
        }

        $nilaiBaru = (float)$this->request->getPost('nilai_sekarang');
        $akun_id   = (int)$this->request->getPost('akun_id');

        $nilaiLama = (float)($row['saldo_terkini'] ?? $row['jumlah']);
        $selisih   = $nilaiBaru - $nilaiLama;

        // update nilai saldo terkini
        $this->items->update($id, [
            'saldo_terkini' => $nilaiBaru
        ]);

        // catat transaksi perubahan nilai (jual / penyusutan / apresiasi)
        if ($akun_id && $selisih != 0) {
            $this->trx->insert([
                'user_id'   => $uid,
                'tanggal'   => date('Y-m-d'),
                'jenis'     => $selisih > 0 ? 'in' : 'out',
                'sumber_id' => $akun_id,
                'kategori'  => 'Aset',
                'deskripsi' => $selisih > 0 
                    ? 'Penjualan aset: ' . $row['deskripsi']
                    : 'Penurunan nilai aset: ' . $row['deskripsi'],
                'jumlah'    => abs($selisih),
            ]);
        }

        return redirect()->to('/aset')->with('message', 'Nilai aset berhasil diperbarui.');
    }

    // =========================
    // HAPUS ASET
    // =========================
    public function delete($id)
    {
        $uid = $this->uid();
        $row = $this->items->find($id);
        if (!$row || $row['user_id'] != $uid || $row['kategori'] != 'aset') {
            return redirect()->back()->with('error', 'Aset tidak ditemukan.');
        }

        $this->items->delete($id);
        return redirect()->to('/aset')->with('message', 'Aset telah dihapus.');
    }
}
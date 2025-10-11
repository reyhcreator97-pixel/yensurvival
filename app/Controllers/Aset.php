<?php

namespace App\Controllers;

use App\Models\AsetModel;
use App\Models\KekayaanItemModel;
use App\Models\TransaksiModel;

class Aset extends BaseController
{
    protected $aset;
    protected $kekayaan;
    protected $trx;

    public function __construct()
    {
        $this->aset     = new AsetModel();
        $this->kekayaan = new KekayaanItemModel();
        $this->trx      = new TransaksiModel();
    }

    private function uid()
    {
        return (int) user_id(); // konsisten dengan halaman lain
    }

    // =======================
    // 📦 HALAMAN UTAMA
    // =======================
    public function index()
    {
        $uid  = $this->uid();
        $list = $this->aset->getAllByUser($uid);

        // Ambil akun uang dari kekayaan awal
        $akun = $this->kekayaan->where([
            'user_id'  => $uid,
            'kategori' => 'uang'
        ])->findAll();

        // Jika data dari kekayaan awal belum tergenerate ke aset
        $awal = $this->kekayaan->where([
            'user_id'  => $uid,
            'kategori' => 'aset'
        ])->findAll();

        // Sinkronisasi data kekayaan awal ke tabel aset
        foreach ($awal as $r) {
            $exists = $this->aset
                ->where('user_id', $uid)
                ->where('nama', $r['deskripsi'])
                // ->where('kategori', 'aset')
                ->first();

            if (!$exists) {
                $this->aset->insert([
                    'user_id'        => $uid,
                    'tanggal'        => date('Y-m-d'),
                    'nama'           => $r['deskripsi'],
                    'akun_id'        => null,
                    'jumlah'         => $r['jumlah'],
                    'nilai_sekarang'  => $r['jumlah'], // ✅ ganti nilai_sekarang jadi saldo_terkini
                    'deskripsi'      => 'Data dari Kekayaan Awal',
                    'status'         => 'aktif',
                    // 'kategori'       => 'aset'
                ]);
            }
        }


    // =======================
    // Hitung Total
    // =======================

       //---- Ambil dari tabel aset
       $totalAsetUtama = array_sum(array_column($list, 'nilai_sekarang'));

       // ---- Ambil dari kekayaan awal kategori aset
        $totalAwal = array_sum(array_column($awal, 'jumlah'));

        // ---- gabungan total keduanya
        $totalAset = $totalAsetUtama + $totalAwal;

        $data = [
            'title'     => 'Aset',
            'list'      => $list,
            'akun'      => $akun,
            'totalAset' => $totalAset,
        ];

        return view('aset/index', $data);
    }

    // =======================
    // ➕ TAMBAH ASET
    // =======================
    public function store()
    {
        $uid      = $this->uid();
        $nama     = $this->request->getPost('nama');
        $akun_id  = $this->request->getPost('akun_id');
        $jumlah   = (float) $this->request->getPost('jumlah');
        $desc     = $this->request->getPost('deskripsi') ?? '';
        $tanggal  = date('Y-m-d');

        if (!$nama || !$akun_id || $jumlah <= 0) {
            return redirect()->back()->with('error', 'Data belum lengkap.');
        }

        $this->aset->insert([
            'user_id'        => $uid,
            'tanggal'        => $tanggal,
            'nama'           => $nama,
            'akun_id'        => $akun_id,
            'jumlah'         => $jumlah,
            'nilai_sekarang'  => $jumlah, // ✅ ganti nilai_sekarang jadi saldo_terkini
            'deskripsi'      => $desc,
            'status'         => 'aktif',
            // 'kategori'       => 'aset'
        ]);

        // Catat ke transaksi
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => $tanggal,
            'jenis'     => 'out',
            'sumber_id' => $akun_id,
            'kategori'  => 'Aset',
            'deskripsi' => "Pembelian aset: $nama",
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/aset')->with('message', 'Aset berhasil ditambahkan.');
    }

    // =======================
    // 🔄 UPDATE NILAI SEKARANG
    // =======================
    public function updateNilai()
    {
        $uid   = $this->uid();
        $id    = $this->request->getPost('id');
        $nilai = (float) $this->request->getPost('nilai_sekarang'); // ✅ kolom disesuaikan

        $row = $this->aset->where(['user_id' => $uid, 'id' => $id])->first();
        if (!$row) {
            return redirect()->back()->with('error', 'Aset tidak ditemukan.');
        }

        $this->aset->update($id, ['nilai_sekarang' => $nilai]); // ✅ kolom disesuaikan

        return redirect()->to('/aset')->with('message', 'Nilai aset diperbarui.');
    }

    // =======================
    // 💰 JUAL ASET
    // =======================
    public function jual()
    {
        $uid       = $this->uid();
        $id        = $this->request->getPost('id');
        $nilai     = (float) $this->request->getPost('nilai_sekarang'); // ✅ kolom disesuaikan
        $akun_id   = $this->request->getPost('akun_id');
        $desc      = $this->request->getPost('deskripsi') ?? '';
        $tanggal   = date('Y-m-d');

        $row = $this->aset->where(['user_id' => $uid, 'id' => $id])->first();
        if (!$row) {
            return redirect()->back()->with('error', 'Aset tidak ditemukan.');
        }

        $this->aset->update($id, [
            'status'        => 'selesai',
            'nilai_sekarang' => $nilai, // ✅ kolom disesuaikan
        ]);

        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => $tanggal,
            'jenis'     => 'in',
            'sumber_id' => $akun_id,
            'kategori'  => 'Aset',
            'deskripsi' => "Penjualan aset: {$row['nama']}",
            'jumlah'    => $nilai,
        ]);

        return redirect()->to('/aset')->with('message', 'Aset berhasil dijual.');
    }

    // =======================
    // 🗑️ HAPUS ASET
    // =======================
    public function delete($id)
    {
        $uid = $this->uid();
        $row = $this->aset->where(['user_id' => $uid, 'id' => $id])->first();

        if (!$row) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $this->aset->delete($id);
        return redirect()->to('/aset')->with('message', 'Data aset dihapus.');
    }
}

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
        $uid = $this->uid();
    
        // 1️⃣ Ambil semua data investasi yang disimpan manual
        $list = $this->aset
            ->where('user_id', $uid)
            ->orderBy('id', 'DESC')
            ->findAll();
    
        // 2️⃣ Ambil juga data dari kekayaan_awal kategori investasi
        $dariKekayaan = $this->kekayaan
            ->where(['user_id' => $uid, 'kategori' => 'aset'])
            ->findAll();
    
        // 3️⃣ Satukan hasil tanpa ubah struktur variabel
        foreach ($dariKekayaan as $r) {
            $list[] = [
                'id'              => $r['id'],
                'tanggal'         => $r['created_at'] ?? '',
                'nama'            => $r['deskripsi'],
                'akun_id'         => null,
                'jumlah'          => $r['jumlah'],
                'nilai_sekarang'  => $r['saldo_terkini'] ?? $r['jumlah'],
                'deskripsi'       => $r['deskripsi'],
                'status'          => 'aktif',
            ];
        }
    
        // 4️⃣ Akun untuk dropdown
        $akun = $this->kekayaan
            ->where(['user_id' => $uid, 'kategori' => 'uang'])
            ->findAll();
    
        // 5️⃣ Hitung total nilai sekarang (tetap sama variabelnya)
        $totalAset = array_sum(array_column($list, 'nilai_sekarang'));
        
    
        $data = [
            'title'          => 'Aset',
            'list'           => $list,
            'akun'           => $akun,
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
        $nilai = (float) $this->request->getPost('nilai_sekarang');
    
        // Coba cari dulu di tabel investasi
        $row = $this->aset->where('id', $id)->first();
    
        if ($row) {
            // ✅ Update dari tabel investasi
            $this->aset->update($id, ['nilai_sekarang' => $nilai]);
            return redirect()->to('/aset')->with('message', 'Nilai Aset diperbarui.');
        }
    
        // Kalau gak ada di tabel investasi, cek di tabel kekayaan_awal
        $cekAwal = $this->kekayaan
            ->where('id', $id)
            ->where('kategori', 'aset')
            ->first();
    
        if ($cekAwal) {
            // ✅ Update dari data kekayaan_awal kategori investasi
            $this->kekayaan->update($id, ['saldo_terkini' => $nilai]);
            return redirect()->to('/aset')->with('message', 'Nilai aset awal diperbarui.');
        }
    
        // Kalau gak ditemukan di dua-duanya
        return redirect()->back()->with('error', 'Aset tidak ditemukan.');
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

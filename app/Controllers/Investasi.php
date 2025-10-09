<?php

namespace App\Controllers;

use App\Models\KekayaanItemModel;
use App\Models\TransaksiModel;
use CodeIgniter\Controller;

class Investasi extends BaseController
{
    protected KekayaanItemModel $items;
    protected TransaksiModel $trx;

    public function __construct()
    {
        $this->items = new KekayaanItemModel();
        $this->trx   = new TransaksiModel();
        helper(['form']);
    }

    private function uid(): int
    {
        return (int) user_id(); // sesuaikan dengan auth-mu
    }

    public function index(): string
    {
        $uid = $this->uid();

        // 🔹 Ambil semua data kategori investasi dari kekayaan awal
        $list = $this->items->where([
            'user_id'  => $uid,
            'kategori' => 'investasi'
        ])->orderBy('id', 'DESC')->findAll();

        // Hitung total
        $totalInvestasi = 0;
        $totalLabaRugi  = 0;
        foreach ($list as &$row) {
            $saldo = $row['saldo_terkini'] ?? $row['jumlah'];
            $row['laba_rugi'] = $saldo - $row['jumlah'];
            $totalInvestasi += $saldo;
            $totalLabaRugi  += $row['laba_rugi'];
        }

        $data = [
            'title'          => 'Investasi',
            'list'           => $list,
            'totalInvestasi' => $totalInvestasi,
            'totalLabaRugi'  => $totalLabaRugi,
        ];

        return view('investasi/index', $data);
    }

    // Tambah investasi baru
    public function store()
    {
        $uid       = $this->uid();
        $nama      = trim($this->request->getPost('nama'));
        $jumlah    = (float) $this->request->getPost('jumlah');
        $akunSumber= (int) $this->request->getPost('akun_id');

        if ($nama === '' || $jumlah <= 0) {
            return redirect()->back()->with('error', 'Nama & jumlah wajib diisi.');
        }

        // Simpan ke kekayaan awal kategori investasi
        $this->items->insert([
            'user_id'       => $uid,
            'kategori'      => 'investasi',
            'deskripsi'     => $nama,
            'jumlah'        => $jumlah,
            'saldo_terkini' => $jumlah,
        ]);

        // Catat transaksi keluar dari akun sumber (beli investasi)
        if ($akunSumber) {
            $this->trx->insert([
                'user_id'   => $uid,
                'tanggal'   => date('Y-m-d'),
                'jenis'     => 'out',
                'sumber_id' => $akunSumber,
                'kategori'  => 'Investasi',
                'deskripsi' => 'Pembelian ' . $nama,
                'jumlah'    => $jumlah,
            ]);
        }

        return redirect()->to('/investasi')->with('message', 'Investasi baru berhasil ditambahkan.');
    }

    // Update nilai sekarang
    public function update($id)
    {
        $uid = $this->uid();
        $nilaiSekarang = (float)$this->request->getPost('nilai_sekarang');
        $akunTujuan    = (int)$this->request->getPost('akun_id');
        $deskripsi     = trim($this->request->getPost('deskripsi'));

        // 🔍 Cari di kekayaan_item kategori investasi
        $inv = $this->items
            ->where(['id' => $id, 'user_id' => $uid, 'kategori' => 'investasi'])
            ->first();

        if (!$inv) {
            return redirect()->back()->with('error', 'Investasi tidak ditemukan.');
        }

        $nilaiLama = (float)($inv['saldo_terkini'] ?? $inv['jumlah']);
        $selisih   = $nilaiSekarang - $nilaiLama;

        // Update saldo investasi
        $this->items->update($inv['id'], [
            'saldo_terkini' => $nilaiSekarang,
            'deskripsi'     => $deskripsi ?: $inv['deskripsi'],
        ]);

        // Kalau ada perubahan nilai (jual / rugi / untung), catat di transaksi
        if ($selisih != 0 && $akunTujuan) {
            $jenis = $selisih > 0 ? 'in' : 'out';
            $this->trx->insert([
                'user_id'   => $uid,
                'tanggal'   => date('Y-m-d'),
                'jenis'     => $jenis,
                'sumber_id' => $akunTujuan,
                'kategori'  => 'Perubahan Nilai Investasi',
                'deskripsi' => $deskripsi ?: 'Update nilai investasi',
                'jumlah'    => abs($selisih),
            ]);
        }

        return redirect()->to('/investasi')->with('message', 'Nilai investasi berhasil diperbarui.');
    }

    // Hapus investasi
    public function delete($id)
    {
        $uid = $this->uid();
        $row = $this->items->where([
            'id' => $id,
            'user_id' => $uid,
            'kategori' => 'investasi'
        ])->first();

        if (!$row) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $this->items->delete($id);
        return redirect()->to('/investasi')->with('message', 'Data investasi dihapus.');
    }
}

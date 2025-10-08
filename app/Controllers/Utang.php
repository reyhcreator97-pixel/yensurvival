<?php

namespace App\Controllers;

use App\Models\UtangModel;
use App\Models\TransaksiModel;
use App\Models\KekayaanItemModel;
use CodeIgniter\Controller;

class Utang extends Controller
{
    protected $utang;
    protected $trx;

    public function __construct()
    {
        $this->utang = new UtangModel();
        $this->trx   = new TransaksiModel();
    }

    private function uid(): int
    {
        return (int) user_id();
    }

    public function index(): string
    {
        $uid = $this->uid();

        // 🔹 Ambil data dari tabel utang
        $listDb = $this->utang->where('user_id', $uid)->orderBy('tanggal', 'DESC')->findAll();

        // 🔹 Ambil juga dari kekayaan_awal (kategori utang)
        $items = new KekayaanItemModel();
        $listKekayaan = $items->where([
            'user_id'  => $uid,
            'kategori' => 'utang'
        ])->findAll();

        // 🔹 Tandai data awal
        foreach ($listKekayaan as &$i) {
            $i['asal'] = 'awal';
            $i['status'] = 'belum';
            $i['tanggal'] = $i['created_at'] ?? date('Y-m-d');
        }

        // 🔹 Gabungkan data
        $list = array_merge($listDb, $listKekayaan);

        // 🔹 Hitung total
        $total = ['utang' => 0, 'lunas' => 0];
        foreach ($list as $r) {
            $status = $r['status'] ?? 'belum';
            $jumlah = (float)($r['jumlah'] ?? 0);

            if ($status === 'belum') $total['utang'] += $jumlah;
            if ($status === 'lunas') $total['lunas'] += $jumlah;
        }

        // 🔹 Sisa gak boleh minus
        $sisa = max(0, $total['utang'] - $total['lunas']);

        return view('utang/index', [
            'title' => 'Catatan Utang',
            'list'  => $list,
            'total' => $total,
            'sisa'  => $sisa,
        ]);
    }

    public function store()
    {
        $uid = $this->uid();
        $tanggal = $this->request->getPost('tanggal');
        $nama = $this->request->getPost('nama');
        $keterangan = $this->request->getPost('keterangan');
        $jumlah = (float) $this->request->getPost('jumlah');

        $this->utang->insert([
            'user_id' => $uid,
            'tanggal' => $tanggal,
            'nama' => $nama,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
            'status' => 'belum'
        ]);

        // Catat ke transaksi (utang = pemasukan)
        $this->trx->insert([
            'user_id'  => $uid,
            'tanggal'  => $tanggal,
            'jenis'    => 'in',
            'kategori' => 'Utang',
            'deskripsi'=> "Pinjam uang dari {$nama}",
            'jumlah'   => $jumlah
        ]);

        return redirect()->to('/utang')->with('message', 'Utang berhasil ditambahkan.');
    }

    public function lunas($id)
    {
        $uid = $this->uid();
        $utang = $this->utang->find($id);
        if (!$utang || $utang['user_id'] != $uid) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $this->utang->update($id, ['status' => 'lunas']);

        // Catat ke transaksi (bayar utang = pengeluaran)
        $this->trx->insert([
            'user_id'  => $uid,
            'tanggal'  => date('Y-m-d'),
            'jenis'    => 'out',
            'kategori' => 'Pembayaran Utang',
            'deskripsi'=> "Bayar utang ke {$utang['nama']}",
            'jumlah'   => $utang['jumlah']
        ]);

        return redirect()->to('/utang')->with('message', 'Utang ditandai lunas.');
    }

    public function delete($id)
    {
        $uid = $this->uid();
        $row = $this->utang->find($id);
        if ($row && $row['user_id'] == $uid) {
            $this->utang->delete($id);
            return redirect()->to('/utang')->with('message', 'Utang dihapus.');
        }
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
}

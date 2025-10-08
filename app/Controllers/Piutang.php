<?php

namespace App\Controllers;

use App\Models\PiutangModel;
use App\Models\TransaksiModel;
use App\Models\KekayaanItemModel;
use CodeIgniter\Controller;

class Piutang extends Controller
{
    protected $piutang;
    protected $trx;

    public function __construct()
    {
        $this->piutang = new PiutangModel();
        $this->trx     = new TransaksiModel();
    }

    private function uid(): int
    {
        return (int) user_id();
    }

    public function index(): string
    {
        $uid = $this->uid();

        // 🔹 Ambil data dari tabel piutang
        $listDb = $this->piutang->where('user_id', $uid)->orderBy('tanggal', 'DESC')->findAll();

        // 🔹 Ambil juga dari kekayaan_awal (kategori piutang)
        $items = new KekayaanItemModel();
        $listKekayaan = $items->where([
            'user_id'  => $uid,
            'kategori' => 'piutang'
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
        $total = ['piutang' => 0, 'lunas' => 0];
        foreach ($list as $r) {
            $status = $r['status'] ?? 'belum';
            $jumlah = (float)($r['jumlah'] ?? 0);

            if ($status === 'belum') $total['piutang'] += $jumlah;
            if ($status === 'lunas') $total['lunas'] += $jumlah;
        }

        // 🔹 Sisa gak boleh minus
        $sisa = max(0, $total['piutang'] - $total['lunas']);

        return view('piutang/index', [
            'title' => 'Catatan Piutang',
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

        $this->piutang->insert([
            'user_id' => $uid,
            'tanggal' => $tanggal,
            'nama' => $nama,
            'keterangan' => $keterangan,
            'jumlah' => $jumlah,
            'status' => 'belum'
        ]);

        // Catat ke transaksi (piutang = pengeluaran)
        $this->trx->insert([
            'user_id'  => $uid,
            'tanggal'  => $tanggal,
            'jenis'    => 'out',
            'kategori' => 'Piutang',
            'deskripsi'=> "Meminjamkan uang ke {$nama}",
            'jumlah'   => $jumlah
        ]);

        return redirect()->to('/piutang')->with('message', 'Piutang berhasil ditambahkan.');
    }

    public function lunas($id)
    {
        $uid = $this->uid();
        $piutang = $this->piutang->find($id);
        if (!$piutang || $piutang['user_id'] != $uid) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $this->piutang->update($id, ['status' => 'lunas']);

        // Catat ke transaksi (dibayar = pemasukan)
        $this->trx->insert([
            'user_id'  => $uid,
            'tanggal'  => date('Y-m-d'),
            'jenis'    => 'in',
            'kategori' => 'Pelunasan Piutang',
            'deskripsi'=> "Pembayaran dari {$piutang['nama']}",
            'jumlah'   => $piutang['jumlah']
        ]);

        return redirect()->to('/piutang')->with('message', 'Piutang ditandai lunas.');
    }

    public function delete($id)
    {
        $uid = $this->uid();
        $row = $this->piutang->find($id);
        if ($row && $row['user_id'] == $uid) {
            $this->piutang->delete($id);
            return redirect()->to('/piutang')->with('message', 'Piutang dihapus.');
        }
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
}

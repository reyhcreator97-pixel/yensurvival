<?php

namespace App\Controllers;

use App\Models\UtangModel;
use App\Models\TransaksiModel;
use App\Models\KekayaanItemModel;

class Utang extends BaseController
{
    protected $utang;
    protected $trx;
    protected $items;

    public function __construct()
    {
        $this->utang = new UtangModel();
        $this->trx   = new TransaksiModel();
        $this->items = new \App\Models\KekayaanItemModel(); // ini sama kayak di aset
    }

    private function uid()
    {
        return (int) user_id(); // konsisten dengan halaman lain
    }

    public function index()
    {
        $uid = $this->uid();

        // Ambil utang dari tabel utang
        $listUtang = $this->utang->where('user_id', $uid)->findAll();

        // Ambil kekayaan awal kategori utang
        $fromKekayaan = $this->items
            ->where('user_id', $uid)
            ->where('kategori', 'utang')
            ->findAll();

        // Ambil akun (uang)
        $akunList = $this->items
            ->where('user_id', $uid)
            ->where('kategori', 'uang')
            ->findAll();

        // Buat map id => nama akun
        $akunMap = [];
        foreach ($akunList as $a) {
            $akunMap[$a['id']] = $a['deskripsi'];
        }

        // Gabungkan data tanpa duplikat nama (prioritaskan utang tabel utama)
        $final = $listUtang;

        foreach ($fromKekayaan as $k) {
            $namaKekayaan = trim(strtolower($k['deskripsi']));

            $exists = false;
            foreach ($listUtang as $u) {
                $namaUtang = trim(strtolower($u['nama'] ?? ''));
                if ($namaUtang === $namaKekayaan) {
                    $exists = true;
                    break;
                }
            }

            if (!$exists) {
                $final[] = [
                    'id'          => 0,
                    'user_id'     => $uid,
                    'akun_id'     => null,
                    'tanggal'     => date('Y-m-d'),
                    'nama'        => $k['deskripsi'],
                    'keterangan'  => 'Kekayaan Awal',
                    'jumlah'      => $k['jumlah'],
                    'dibayar'     => 0,
                    'status'      => 'belum'
                ];
            }
        }

        // Tambahkan nama akun ke setiap data
        foreach ($final as &$r) {
            if (!empty($r['akun_id']) && isset($akunMap[$r['akun_id']])) {
                $r['nama_akun'] = $akunMap[$r['akun_id']];
            } else {
                $r['nama_akun'] = '-';
            }
        }

        // --- Hitung total
        $totalUtang = array_sum(array_map(function ($r) {
            $j = (float)($r['jumlah']  ?? 0);
            $d = (float)($r['dibayar'] ?? 0);
            return max(0, $j - $d); // sisa aktif aja
        }, $final));

        $totalBayar = array_sum(array_map(function ($r) {
            return (float)($r['dibayar'] ?? 0);
        }, $final));


        return view('utang/index', [
            'title'       => 'Utang',
            'list'        => $final,
            'akun'        => $akunList,
            'totalUtang'  => $totalUtang,
            'totalBayar'  => $totalBayar,
        ]);
    }


    public function store()
    {
        $uid       = $this->uid();
        $nama      = trim($this->request->getPost('nama'));
        $jumlah    = (float) $this->request->getPost('jumlah');
        $akun_id   = (int) $this->request->getPost('akun_id');
        $desc      = trim($this->request->getPost('keterangan'));

        // Simpan utang
        $this->utang->insert([
            'user_id'    => $uid,
            'tanggal'   => date('Y-m-d'),
            'nama'       => $nama,
            'jumlah'     => $jumlah,
            'dibayar'    => 0,
            'akun_id'    => $akun_id,
            'keterangan' => $desc ?: 'Utang baru',
        ]);

        // Transaksi otomatis (uang keluar)
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'in',
            'sumber_id' => $akun_id,
            'kategori'  => 'Utang',
            'deskripsi' => 'Menerima Utang ' . $nama,
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/utang')->with('message', 'Utang baru ditambahkan.');
    }
    public function storePembayaran()
    {
        $uid = user_id();
        $utangId = (int)$this->request->getPost('utang_id');
        $akunId  = (int)$this->request->getPost('akun_id');
        $jumlah  = (float)$this->request->getPost('jumlah');

        if (!$akunId || $jumlah <= 0) {
            return redirect()->back()->with('error', 'Data pembayaran tidak lengkap.');
        }

        // Kalau data dari kekayaan awal (id=0) → bikin entri baru di tabel utang
        if ($utangId === 0) {
            $nama = $this->request->getPost('nama') ?? 'Utang Kekayaan Awal';

            // ✅ ambil jumlah utang asli dari kekayaan_items
            $fromKekayaan = $this->items
                ->where('user_id', $uid)
                ->where('kategori', 'utang')
                ->where('deskripsi', $nama)
                ->first();

            $jumlahAsli = $fromKekayaan['jumlah'] ?? $jumlah;

            $utangId = $this->utang->insert([
                'user_id' => $uid,
                'akun_id' => $akunId,
                'tanggal' => date('Y-m-d'),
                'nama' => $nama,
                'keterangan' => 'Kekayaan Awal (Auto)',
                'jumlah' => $jumlahAsli, // ✅ total utang asli
                'dibayar' => 0,
                'status' => 'belum'
            ]);
        }

        $utang = $this->utang->find($utangId);
        if (!$utang || $utang['user_id'] != $uid) {
            return redirect()->back()->with('error', 'Data utang tidak ditemukan.');
        }

        // --- Update total dibayar
        $baru = $utang['dibayar'] + $jumlah;
        $status = $baru >= $utang['jumlah'] ? 'lunas' : 'belum';
        $this->utang->update($utangId, [
            'dibayar' => $baru,
            'status'  => $status
        ]);

        // --- Catat ke transaksi
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'out',
            'sumber_id' => $akunId,
            'kategori'  => 'Utang',
            'deskripsi' => 'Pembayaran utang: ' . $utang['nama'],
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/utang')->with('message', 'Pembayaran utang berhasil.');
    }

    public function delete($id)
    {
        $uid = $this->uid();
        $utang = $this->utang->find($id);

        if (!$utang || $utang['user_id'] != $uid) {
            return redirect()->back()->with('error', 'Data utang tidak ditemukan.');
        }

        $this->utang->delete($id);

        // --- Hapus dari kekayaan awal jika ada nama yang sama
        $this->items
            ->where('user_id', $uid)
            ->where('kategori', 'utang')
            ->where('deskripsi', $utang['nama'])
            ->delete();

        return redirect()->to('/utang')->with('message', 'Utang berhasil dihapus.');
    }
}

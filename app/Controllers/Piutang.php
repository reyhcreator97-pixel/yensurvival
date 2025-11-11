<?php

namespace App\Controllers;

use App\Models\PiutangModel;
use App\Models\TransaksiModel;
use App\Models\KekayaanItemModel;

class Piutang extends BaseController
{
    protected $piutang;
    protected $trx;
    protected $items;

    public function __construct()
    {
        $this->piutang = new PiutangModel();
        $this->trx     = new TransaksiModel();
        $this->items   = new \App\Models\KekayaanItemModel(); // sama kayak di utang
    }

    private function uid()
    {
        return (int) user_id();
    }

    public function index()
    {
        $uid = $this->uid();

        // --- Ambil data piutang user
        $listPiutang = $this->piutang->where('user_id', $uid)->findAll();

        // --- Ambil data dari kekayaan awal kategori piutang
        $fromKekayaan = $this->items
            ->where('user_id', $uid)
            ->where('kategori', 'piutang')
            ->findAll();

        // --- Ambil daftar akun (uang)
        $akunList = $this->items
            ->where('user_id', $uid)
            ->where('kategori', 'uang')
            ->findAll();

        // --- Mapping id → nama akun
        $akunMap = [];
        foreach ($akunList as $a) {
            $akunMap[$a['id']] = $a['deskripsi'];
        }

        // --- Gabungkan tanpa duplikasi nama
        $final = $listPiutang;
        foreach ($fromKekayaan as $k) {
            $namaKekayaan = trim(strtolower($k['deskripsi']));
            $exists = false;

            foreach ($listPiutang as $p) {
                $namaPiutang = trim(strtolower($p['nama'] ?? ''));
                if ($namaPiutang === $namaKekayaan) {
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

        // --- Tambahkan nama akun ke setiap data
        foreach ($final as &$r) {
            if (!empty($r['akun_id']) && isset($akunMap[$r['akun_id']])) {
                $r['nama_akun'] = $akunMap[$r['akun_id']];
            } else {
                $r['nama_akun'] = '-';
            }
        }

        // --- Hitung total
        $totalPiutang = array_sum(array_column($final, 'jumlah'));
        $totalTerima  = array_sum(array_map(fn($r) => (float)($r['dibayar'] ?? 0), $final));

        return view('piutang/index', [
            'title'        => 'Piutang',
            'list'         => $final,
            'akun'         => $akunList,
            'totalPiutang' => $totalPiutang,
            'totalTerima'  => $totalTerima,
        ]);
    }



    public function store()
    {
        $uid       = $this->uid();
        $nama      = trim($this->request->getPost('nama'));
        $jumlah    = (float) $this->request->getPost('jumlah');
        $akun_id   = (int) $this->request->getPost('akun_id');
        $desc      = trim($this->request->getPost('keterangan'));

        // Simpan piutang
        $this->piutang->insert([
            'user_id'    => $uid,
            'tanggal'   => date('Y-m-d'),
            'nama'       => $nama,
            'jumlah'     => $jumlah,
            'dibayar'    => 0,
            'akun_id'    => $akun_id,
            'keterangan' => $desc ?: 'Piutang baru',
        ]);

        // Transaksi otomatis (uang keluar → memberi piutang)
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'out',
            'sumber_id' => $akun_id,
            'kategori'  => 'Piutang',
            'deskripsi' => 'Memberi Piutang: ' . $nama,
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/piutang')->with('message', 'Piutang baru ditambahkan.');
    }

    public function storePembayaran()
    {
        $uid = user_id();
        $piutangId = (int)$this->request->getPost('piutang_id');
        $akunId  = (int)$this->request->getPost('akun_id');
        $jumlah  = (float)$this->request->getPost('jumlah');

        if (!$akunId || $jumlah <= 0) {
            return redirect()->back()->with('error', 'Data penerimaan tidak lengkap.');
        }

        // Kalau data dari kekayaan awal (id=0) → bikin entri baru di tabel piutang
        if ($piutangId === 0) {
            $nama = $this->request->getPost('nama') ?? 'Piutang Kekayaan Awal';

            // ✅ ambil jumlah piutang asli dari kekayaan_items
            $fromKekayaan = $this->items
                ->where('user_id', $uid)
                ->where('kategori', 'piutang')
                ->where('deskripsi', $nama)
                ->first();

            $jumlahAsli = $fromKekayaan['jumlah'] ?? $jumlah;

            $piutangId = $this->piutang->insert([
                'user_id' => $uid,
                'akun_id' => $akunId,
                'tanggal' => date('Y-m-d'),
                'nama' => $nama,
                'keterangan' => 'Kekayaan Awal (Auto)',
                'jumlah' => $jumlahAsli,
                'dibayar' => 0,
                'status' => 'belum'
            ]);
        }

        $piutang = $this->piutang->find($piutangId);
        if (!$piutang || $piutang['user_id'] != $uid) {
            return redirect()->back()->with('error', 'Data piutang tidak ditemukan.');
        }

        // --- Update total diterima
        $baru = $piutang['dibayar'] + $jumlah;
        $status = $baru >= $piutang['jumlah'] ? 'lunas' : 'belum';
        $this->piutang->update($piutangId, [
            'dibayar' => $baru,
            'status'  => $status
        ]);

        // --- Catat ke transaksi
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'in', // uang masuk
            'sumber_id' => $akunId,
            'kategori'  => 'Piutang',
            'deskripsi' => 'Penerimaan piutang: ' . $piutang['nama'],
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/piutang')->with('message', 'Penerimaan piutang berhasil.');
    }

    public function delete($id)
    {
        $uid = $this->uid();
        $piutang = $this->piutang->find($id);

        if (!$piutang || $piutang['user_id'] != $uid) {
            return redirect()->back()->with('error', 'Data piutang tidak ditemukan.');
        }

        $this->piutang->delete($id);

        // --- Hapus dari kekayaan awal jika ada nama yang sama
        $this->items
            ->where('user_id', $uid)
            ->where('kategori', 'piutang')
            ->where('deskripsi', $piutang['nama'])
            ->delete();

        return redirect()->to('/piutang')->with('message', 'Piutang berhasil dihapus.');
    }
}

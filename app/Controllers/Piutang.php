<?php
namespace App\Controllers;

use App\Models\PiutangModel;
use App\Models\KekayaanItemModel;
use App\Models\TransaksiModel;

class Piutang extends BaseController
{
    protected $piutang, $akun, $trx;

    public function __construct()
    {
        $this->piutang = new PiutangModel();
        $this->akun    = new KekayaanItemModel();
        $this->trx     = new TransaksiModel();
    }

    private function uid(): int
    {
        return (int) user_id();
    }

    public function index(): string
    {
        $uid = $this->uid();
    
        // Ambil data dari tabel piutang
        $listPiutang = $this->piutang->where('user_id', $uid)->findAll();
    
        // Ambil juga dari kekayaan awal
        $fromKekayaan = $this->akun
            ->where(['user_id' => $uid, 'kategori' => 'piutang'])
            ->findAll();
    
        // Gabungkan tanpa duplikasi nama
        $final = $listPiutang;
        foreach ($fromKekayaan as $k) {
            $exists = false;
            foreach ($listPiutang as $p) {
                if (trim(strtolower($p['nama'])) === trim(strtolower($k['deskripsi']))) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $final[] = [
                    'id'         => 0,
                    'user_id'    => $uid,
                    'nama'       => $k['deskripsi'],
                    'jumlah'     => (float)$k['jumlah'],
                    'dibayar'    => 0,
                    'akun_id'    => null,
                    'keterangan' => 'Kekayaan Awal',
                ];
            }
        }
    
        // Hitung total
        $totalPiutang = 0;
        $totalDibayar = 0;
        foreach ($final as $r) {
            $totalPiutang += (float)($r['jumlah'] ?? 0);
            $totalDibayar += (float)($r['dibayar'] ?? 0);
        }
        $sisaPiutang = max(0, $totalPiutang - $totalDibayar);
    
        // Ambil akun uang
        $akun = $this->akun
            ->where(['user_id' => $uid, 'kategori' => 'uang'])
            ->orderBy('id', 'ASC')
            ->findAll();
    
        $data = [
            'title'        => 'Catatan Piutang',
            'list'         => $final,
            'akun'         => $akun,
            'totalPiutang' => $totalPiutang,
            'totalDibayar' => $totalDibayar,
            'sisaPiutang'  => $sisaPiutang,
        ];
    
        return view('piutang/index', $data);
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
            'nama'       => $nama,
            'jumlah'     => $jumlah,
            'dibayar'    => 0,
            'akun_id'    => $akun_id,
            'keterangan' => $desc ?: 'Piutang baru',
        ]);

        // Transaksi otomatis (uang keluar)
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'out',
            'sumber_id' => $akun_id,
            'kategori'  => 'Piutang',
            'deskripsi' => 'Pemberian piutang kepada ' . $nama,
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/piutang')->with('message', 'Piutang baru ditambahkan.');
    }

    public function terima($id)
    {
        $uid = $this->uid();
        $piutang = $this->piutang->find($id);
        if (!$piutang || $piutang['user_id'] != $uid) {
            return redirect()->back()->with('error', 'Data piutang tidak ditemukan.');
        }

        $jumlahTerima = (float) $this->request->getPost('jumlah_terima');
        $akunTerima   = (int) $this->request->getPost('akun_terima');

        // Update jumlah dibayar
        $baru = $piutang['dibayar'] + $jumlahTerima;
        $this->piutang->update($id, ['dibayar' => $baru]);

        // Transaksi otomatis (uang masuk)
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'in',
            'sumber_id' => $akunTerima,
            'kategori'  => 'Pelunasan Piutang',
            'deskripsi' => 'Pelunasan piutang dari ' . $piutang['nama'],
            'jumlah'    => $jumlahTerima,
        ]);

        return redirect()->to('/piutang')->with('message', 'Pelunasan piutang berhasil.');
    }

    public function storePembayaran()
{
    $uid = user_id();
    $piutangId = (int)$this->request->getPost('piutang_id');
    $akunId    = (int)$this->request->getPost('akun_id');
    $jumlah    = (float)$this->request->getPost('jumlah');

    if (!$piutangId || !$akunId || $jumlah <= 0) {
        return redirect()->back()->with('error', 'Data pembayaran tidak lengkap.');
    }

    // --- 1. Update data piutang (tambah jumlah diterima)
    $piutangModel = new \App\Models\PiutangModel();
    $piutang = $piutangModel->find($piutangId);
    if ($piutang) {
        $diterima = ($piutang['dibayar'] ?? 0) + $jumlah;
        $status   = $diterima >= $piutang['jumlah'] ? 'lunas' : 'belum';
        $piutangModel->update($piutangId, [
            'dibayar' => $diterima,
            'status'  => $status,
            'akun_id' => $akunId,
        ]);
    }

    // --- 2. Catat ke transaksi (biar dashboard & saldo sinkron)
    $trx = new \App\Models\TransaksiModel();
    $trx->insert([
        'user_id'   => $uid,
        'tanggal'   => date('Y-m-d'),
        'jenis'     => 'in',
        'sumber_id' => $akunId,
        'kategori'  => 'Terima Piutang',
        'deskripsi' => 'Penerimaan piutang ID '.$piutangId,
        'jumlah'    => $jumlah,
    ]);

    return redirect()->to('/piutang')->with('message', 'Penerimaan piutang berhasil disimpan.');
}

}

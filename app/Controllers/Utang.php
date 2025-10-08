<?php
namespace App\Controllers;

use App\Models\UtangModel;
use App\Models\KekayaanItemModel;
use App\Models\TransaksiModel;

class Utang extends BaseController
{
    protected $utang, $akun, $trx;

    public function __construct()
    {
        $this->utang = new UtangModel();
        $this->akun  = new KekayaanItemModel();
        $this->trx   = new TransaksiModel();
    }

    private function uid(): int
    {
        return (int) user_id();
    }

    public function index(): string
    {
        $uid = $this->uid();
    
        // Ambil data dari tabel utang
        $listUtang = $this->utang->where('user_id', $uid)->findAll();
    
        // Ambil juga dari kekayaan awal
        $fromKekayaan = $this->akun
            ->where(['user_id' => $uid, 'kategori' => 'utang'])
            ->findAll();
    
        // Gabungkan tanpa duplikasi nama
        $final = $listUtang;
        foreach ($fromKekayaan as $k) {
            $exists = false;
            foreach ($listUtang as $u) {
                if (trim(strtolower($u['nama'])) === trim(strtolower($k['deskripsi']))) {
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
        $totalUtang = 0;
        $totalBayar = 0;
        foreach ($final as $r) {
            $totalUtang += (float)($r['jumlah'] ?? 0);
            $totalBayar += (float)($r['dibayar'] ?? 0);
        }
        $sisaUtang = max(0, $totalUtang - $totalBayar);
    
        // Ambil akun uang
        $akun = $this->akun
            ->where(['user_id' => $uid, 'kategori' => 'uang'])
            ->orderBy('id', 'ASC')
            ->findAll();

    
        $data = [
            'title'      => 'Catatan Utang',
            'list'       => $final,
            'akun'       => $akun,
            'totalUtang' => $totalUtang,
            'totalBayar' => $totalBayar,
            'sisaUtang'  => $sisaUtang,
        ];
    
        return view('utang/index', $data);
    }
    

    public function store()
    {
        $uid       = $this->uid();
        $nama      = trim($this->request->getPost('nama'));
        $jumlah    = (float) $this->request->getPost('jumlah');
        $akun_id   = (int) $this->request->getPost('akun_id');
        $desc      = trim($this->request->getPost('keterangan'));

        if ($jumlah <= 0) {
            return redirect()->back()->with('error', 'Jumlah harus lebih besar dari 0.');
        }

        // Simpan ke tabel utang
        $this->utang->insert([
            'user_id'    => $uid,
            'nama'       => $nama,
            'jumlah'     => $jumlah,
            'dibayar'    => 0,
            'akun_id'    => $akun_id,
            'keterangan' => $desc ?: 'Utang baru',
        ]);

        // Buat transaksi otomatis (pemasukan)
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'in',
            'sumber_id' => $akun_id,
            'kategori'  => 'Utang',
            'deskripsi' => 'Penerimaan uang utang dari ' . $nama,
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/utang')->with('message', 'Utang baru ditambahkan.');
    }

    public function bayar($id)
    {
        $uid = $this->uid();
        $utang = $this->utang->find($id);
        if (!$utang || $utang['user_id'] != $uid) {
            return redirect()->back()->with('error', 'Data utang tidak ditemukan.');
        }

        $jumlahBayar = (float) $this->request->getPost('jumlah_bayar');
        $akunBayar   = (int) $this->request->getPost('akun_bayar');

        // Update total dibayar
        $baru = $utang['dibayar'] + $jumlahBayar;
        $this->utang->update($id, ['dibayar' => $baru]);

        // Buat transaksi otomatis (pengeluaran)
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => date('Y-m-d'),
            'jenis'     => 'out',
            'sumber_id' => $akunBayar,
            'kategori'  => 'Pembayaran Utang',
            'deskripsi' => 'Pembayaran utang kepada ' . $utang['nama'],
            'jumlah'    => $jumlahBayar,
        ]);

        return redirect()->to('/utang')->with('message', 'Pembayaran utang berhasil.');
    }

    public function storePembayaran()
{
    $uid = user_id();
    $utangId = (int)$this->request->getPost('utang_id');
    $akunId  = (int)$this->request->getPost('akun_id');
    $jumlah  = (float)$this->request->getPost('jumlah');

    if (!$utangId || !$akunId || $jumlah <= 0) {
        return redirect()->back()->with('error', 'Data pembayaran tidak lengkap.');
    }


    // --- 1. Update data utang (tambah jumlah dibayar)
    $utangModel = new \App\Models\UtangModel();
    $utang = $utangModel->find($utangId);
    if ($utang) {
        $dibayar = ($utang['dibayar'] ?? 0) + $jumlah;
        $status  = $dibayar >= $utang['jumlah'] ? 'lunas' : 'belum';
        $utangModel->update($utangId, [
            'dibayar' => $dibayar,
            'status'  => $status,
            'akun_id' => $akunId,
        ]);
    }

    // --- 2. Catat ke transaksi (biar dashboard & saldo sinkron)
    $trx = new \App\Models\TransaksiModel();
    $trx->insert([
        'user_id'   => $uid,
        'tanggal'   => date('Y-m-d'),
        'jenis'     => 'out',
        'sumber_id' => $akunId,
        'kategori'  => 'Bayar Utang',
        'deskripsi' => 'Pembayaran utang ID '.$utangId,
        'jumlah'    => $jumlah,
    ]);

    return redirect()->to('/utang')->with('message', 'Pembayaran utang berhasil disimpan.');
}

}

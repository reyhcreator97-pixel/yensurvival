<?php
namespace App\Controllers;

use App\Models\TransaksiModel;
use App\Models\KekayaanItemModel;

class Transaksi extends BaseController
{
    protected TransaksiModel $trx;
    protected KekayaanItemModel $items;

    public function __construct()
    {
        $this->trx   = new TransaksiModel();
        $this->items = new KekayaanItemModel();
    }

    private function uid(): int
    {
        return (int) user_id();
    }

    public function index(): string
    {
        $uid = $this->uid();
    
        // === Filter: daily / monthly / yearly ===
        $mode  = $this->request->getGet('mode')  ?? 'daily';
        $date  = $this->request->getGet('date')  ?? date('Y-m-d');
        $month = $this->request->getGet('month') ?? date('Y-m');
        $year  = $this->request->getGet('year')  ?? date('Y');

        // ambil tanggal terbaru dari transaksi kalau filter harian kosong
// if ($mode === 'daily' && !$this->request->getGet('date')) {
//     $lastDate = $this->trx->selectMax('tanggal')->where('user_id', $uid)->get()->getRow('tanggal');
//     if ($lastDate) {
//         $date = $lastDate;
//     }
// }

    
        // Helper filter universal
        $applyFilter = function($builder) use ($mode, $date, $month, $year) {
            if ($mode === 'daily') {
                // ✅ cocok untuk kolom tipe DATE
                $builder->where('tanggal', $date);
            } elseif ($mode === 'monthly') {
                [$y, $m] = explode('-', $month);
                $builder->where('YEAR(tanggal)', $y)
                        ->where('MONTH(tanggal)', $m);
            } else {
                $builder->where('YEAR(tanggal)', $year);
            }
            return $builder;
        };
        
        
    
        // --- Ambil semua transaksi (tidak filter kategori apapun) ---
        $trxModel = $this->trx; // clone biar query tidak nempel
        $builderList = $trxModel->builder();
        $builderList->where('user_id', $uid);
        $applyFilter($builderList);
        $builderList->orderBy('tanggal', 'DESC');
        $list = $builderList->get()->getResultArray();
    
        // --- Total Pemasukan ---
        $builderIn = $trxModel->builder();
        $builderIn->selectSum('jumlah')
                  ->where('user_id', $uid)
                  ->groupStart()
                      ->where('jenis', 'in')
                      ->orWhere('jenis', 'pemasukan')
                  ->groupEnd();
        $applyFilter($builderIn);
        $rowIn = $builderIn->get()->getRow();
        $totalIn = (float)($rowIn->jumlah ?? 0);
    
        // --- Total Pengeluaran ---
        $builderOut = $trxModel->builder();
        $builderOut->selectSum('jumlah')
                   ->where('user_id', $uid)
                   ->groupStart()
                       ->where('jenis', 'out')
                       ->orWhere('jenis', 'pengeluaran')
                   ->groupEnd();
        $applyFilter($builderOut);
        $rowOut = $builderOut->get()->getRow();
        $totalOut = (float)($rowOut->jumlah ?? 0);
    
        // ✅ Saldo hasil perhitungan in - out
        $saldo = $totalIn - $totalOut;
    
        // --- Ambil daftar akun (uang) ---
        $akun = $this->items->where([
            'user_id'  => $uid,
            'kategori' => 'uang'
        ])->orderBy('id', 'ASC')->findAll();
    
        $data = [
            'title'     => 'Transaksi',
            'mode'      => $mode,
            'date'      => $date,
            'month'     => $month,
            'year'      => $year,
            'list'      => $list,
            'totalIn'   => $totalIn,
            'totalOut'  => $totalOut,
            'saldo'     => $saldo,
            'akun'      => $akun,
        ];

        // dd([
        //     'today' => $date,
        //     'sampel' => $this->trx->orderBy('id', 'desc')->limit(5)->findAll(),
        // ]);

        return view('transaksi/index', $data);
    }
    
    

    // Tambah pemasukan/pengeluaran
    public function store()
    {
        $uid   = $this->uid();
        $jenis = $this->request->getPost('jenis'); // in|out
        $tanggal   = $this->request->getPost('tanggal');
        $sumber_id = (int) $this->request->getPost('sumber_id') ?: null;
        $kategori  = trim((string) $this->request->getPost('kategori'));
        $desc      = trim((string) $this->request->getPost('deskripsi'));
        $jumlah    = (float) str_replace(',', '', (string) $this->request->getPost('jumlah'));

        if (!in_array($jenis, ['in', 'out', 'pemasukan', 'pengeluaran'], true)) {
            return redirect()->back()->with('error', 'Jenis transaksi tidak valid.');
        }

        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => $tanggal ?: date('Y-m-d'),
            'jenis'     => $jenis,
            'sumber_id' => $sumber_id,
            'kategori'  => $kategori ?: null,
            'deskripsi' => $desc ?: null,
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/transaksi')->with('message', 'Transaksi tersimpan.');
    }

    // Pindah dana antar akun (buat 2 baris: out dan in)
    public function transfer()
    {
        $uid      = $this->uid();
        $tanggal  = $this->request->getPost('tanggal') ?: date('Y-m-d');
        $from     = (int) $this->request->getPost('from_id');
        $to       = (int) $this->request->getPost('to_id');
        $jumlah   = (float) str_replace(',', '', (string) $this->request->getPost('jumlah'));
        $cat      = trim((string) $this->request->getPost('cat')) ?: 'Transfer';

        if ($from === $to || $jumlah <= 0) {
            return redirect()->back()->with('error', 'Akun tujuan harus berbeda & jumlah > 0.');
        }

        // out
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => $tanggal,
            'jenis'     => 'out',
            'sumber_id' => $from,
            'tujuan_id' => $to,
            'kategori'  => $cat,
            'deskripsi' => 'Transfer keluar',
            'jumlah'    => $jumlah,
        ]);

        // in
        $this->trx->insert([
            'user_id'   => $uid,
            'tanggal'   => $tanggal,
            'jenis'     => 'in',
            'sumber_id' => $to,
            'tujuan_id' => $from,
            'kategori'  => $cat,
            'deskripsi' => 'Transfer masuk',
            'jumlah'    => $jumlah,
        ]);

        return redirect()->to('/transaksi')->with('message', 'Transfer berhasil.');
    }

    public function delete($id)
    {
        $uid = $this->uid();
        $row = $this->trx->find((int)$id);
    
        if ($row && (int)$row['user_id'] === $uid) {
            // --- rollback saldo akun ---
            $item = new \App\Models\KekayaanItemModel();
    
            if (!empty($row['sumber_id'])) {
                $akun = $item->find($row['sumber_id']);
                if ($akun) {
                    $saldo = isset($akun['saldo_terkini']) && $akun['saldo_terkini'] !== null
                            ? (float)$akun['saldo_terkini']
                            : (float)$akun['jumlah'];
    
                    // Kembalikan saldo sesuai jenis transaksi
                    if ($row['jenis'] === 'out') {
                        $saldo += (float)$row['jumlah']; // transaksi keluar → balikin saldo
                    } elseif ($row['jenis'] === 'in') {
                        $saldo -= (float)$row['jumlah']; // transaksi masuk → kurangi lagi
                    }
    
                    $item->update($akun['id'], ['saldo_terkini' => $saldo]);
                }
            }
    
            // --- hapus data transaksi ---
            $this->trx->delete($row['id']);
            return redirect()->to('/transaksi')->with('message', 'Transaksi dihapus & saldo diperbarui.');
        }
    
        return redirect()->back()->with('error', 'Data tidak ditemukan.');
    }
    

    public function addAkun()
    {
        $uid    = (int) user_id();
        $nama   = trim((string)$this->request->getPost('deskripsi'));
        $jumlah = (float) $this->request->getPost('jumlah');
    
        if ($nama === '' || $jumlah < 0) {
            return redirect()->back()->with('error', 'Nama akun dan saldo awal wajib diisi.');
        }
    
        // 1) buat akun dengan saldo_terkini = 0 dulu
        $item = new \App\Models\KekayaanItemModel();
        $item->insert([
            'user_id'       => $uid,
            'kategori'      => 'uang',
            'deskripsi'     => $nama,
            'jumlah'        => $jumlah,     // hanya sebagai catatan setup awal
            'saldo_terkini' => 0,           // <- penting! mulai dari 0
        ]);
        $akunId = $item->getInsertID();
    
        // 2) insert transaksi "Modal Awal" supaya hook menambah saldo_terkini jadi = $jumlah
        if ($jumlah > 0) {
            $this->trx->insert([
                'user_id'   => $uid,
                'tanggal'   => date('Y-m-d'),
                'jenis'     => 'in',
                'sumber_id' => $akunId,
                'kategori'  => 'Modal Awal',
                'deskripsi' => 'Saldo awal akun baru',
                'jumlah'    => $jumlah,
            ]);
        }
    
        return redirect()->to('/transaksi')->with('message', 'Akun baru berhasil ditambahkan.');
    }
    
}
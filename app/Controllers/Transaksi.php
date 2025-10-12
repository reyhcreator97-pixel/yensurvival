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

  // === Filter: daily / monthly / yearly (tetap sama dengan sebelumnya) ===
  $mode  = $this->request->getGet('mode')  ?? 'daily';       // daily|monthly|yearly
  $date  = $this->request->getGet('date')  ?? date('Y-m-d'); // utk daily
  $month = $this->request->getGet('month') ?? date('Y-m');   // utk monthly (YYYY-MM)
  $year  = $this->request->getGet('year')  ?? date('Y');     // utk yearly (YYYY)

  // Helper untuk menerapkan filter yang sama ke beberapa builder
  $applyFilter = function($builder) use ($mode, $date, $month, $year) {
      if ($mode === 'daily') {
          $builder->where('tanggal', $date);
      } elseif ($mode === 'monthly') {
          [$y,$m] = explode('-', $month);
          $builder->where('YEAR(tanggal)', $y)
                  ->where('MONTH(tanggal)', $m);
      } else { // yearly
          $builder->where('YEAR(tanggal)', $year);
      }
      return $builder;
  };

  // --- Data tabel (list) sesuai filter ---
  $builderList = $this->trx->where('user_id', $uid);
  $applyFilter($builderList);
  $list = $builderList->orderBy('tanggal','DESC')->findAll();

  // --- Totals sesuai filter (dipakai 3 card) ---
  // Total pemasukan: jenis 'in' (atau kompatibel 'pemasukan')
  $builderIn = $this->trx->selectSum('jumlah')
                         ->where('user_id', $uid)
                         ->groupStart()
                             ->where('jenis', 'in')
                             ->orWhere('jenis', 'pemasukan')
                         ->groupEnd();
  $applyFilter($builderIn);
  $rowIn    = $builderIn->get()->getRow();
  $totalIn  = (float)($rowIn->jumlah ?? 0);

  // Total pengeluaran: jenis 'out' (atau kompatibel 'pengeluaran')
  $builderOut = $this->trx->selectSum('jumlah')
                          ->where('user_id', $uid)
                          ->groupStart()
                              ->where('jenis', 'out')
                              ->orWhere('jenis', 'pengeluaran')
                          ->groupEnd();
  $applyFilter($builderOut);
  $rowOut    = $builderOut->get()->getRow();
  $totalOut  = (float)($rowOut->jumlah ?? 0);

  $saldo = $totalIn - $totalOut;


        // --- Ambil total saldo real dari akun (uang) ---
        $totalSaldoAkun = (float) ($this->items
            ->where('user_id', $uid)
            ->where('kategori', 'uang')
            ->selectSum('saldo_terkini')
            ->get()->getRow('saldo_terkini') ?? 0);

        // --- Ambil daftar akun dari kekayaan awal (kategori uang) ---
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
            'saldo'     => $totalSaldoAkun, // ✅ saldo real dari akun
            'akun'      => $akun,
        ];

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
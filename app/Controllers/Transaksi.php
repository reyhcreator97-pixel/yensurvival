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

        $catModel = new \App\Models\TransactionCategoryModel();
        $kategoriList = $catModel->orderBy('type', 'ASC')->findAll();

        // === Filter: daily / monthly / yearly ===
        $mode  = $this->request->getGet('mode')  ?? 'daily';
        $date  = $this->request->getGet('date')  ?? date('Y-m-d');
        $month = $this->request->getGet('month') ?? date('Y-m');
        $year  = $this->request->getGet('year')  ?? date('Y');

        // Helper filter universal
        $applyFilter = function ($builder) use ($mode, $date, $month, $year) {
            if ($mode === 'daily') {
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

        // === PAGINATION SIMPLE (SEPERTI DEVELOPMENT) ===
        $perPage = 10;
        $page    = $this->request->getVar('page') ?? 1;

        // Builder transaksi
        $builderList = $this->trx
            ->where('user_id', $uid);

        $applyFilter($builderList);
        $builderList
            ->orderBy('tanggal', 'DESC')
            ->orderBy('id', 'DESC');

        // 🔥 Ambil data dengan paginate()
        $list = $builderList->paginate($perPage, 'trx');
        $pager = $this->trx->pager;

        // --- Total Pemasukan ---
        $builderIn = $this->trx->builder();
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
        $builderOut = $this->trx->builder();
        $builderOut->selectSum('jumlah')
            ->where('user_id', $uid)
            ->groupStart()
            ->where('jenis', 'out')
            ->orWhere('jenis', 'pengeluaran')
            ->groupEnd();
        $applyFilter($builderOut);
        $rowOut = $builderOut->get()->getRow();
        $totalOut = (float)($rowOut->jumlah ?? 0);

        $saldo = $totalIn - $totalOut;

        // --- Ambil daftar akun (uang) ---
        $akun = $this->items->where([
            'user_id'  => $uid,
            'kategori' => 'uang'
        ])->orderBy('id', 'ASC')->findAll();

        $iconMap = [];
        foreach ($kategoriList as $k) {
            $iconMap[$k['name']] = $k['icon'];
        }

        $categories = model('TransactionCategoryModel')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('transaksi/index', [
            'title'     => 'Transaksi',
            'mode'      => $mode,
            'date'      => $date,
            'month'     => $month,
            'year'      => $year,
            'list'      => $list,
            'pager'     => $pager,
            'totalIn'   => $totalIn,
            'totalOut'  => $totalOut,
            'saldo'     => $saldo,
            'akun'      => $akun,
            'kategoriList' => $kategoriList,
            'iconMap'   => $iconMap,
            'categories' => $categories,

        ]);
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

        // 🔹 biaya admin (opsional)
        $biaya_admin = (float) str_replace(',', '', (string) ($this->request->getPost('biaya_admin') ?? 0));

        if ($from === $to || $jumlah <= 0) {
            return redirect()->back()->with('error', 'Akun tujuan harus berbeda & jumlah > 0.');
        }

        // 1) TRANSFER KELUAR (OUT) DARI AKUN FROM
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

        // 2) TRANSFER MASUK (IN) KE AKUN TO
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

        // 3) BIAYA ADMIN (KALAU ADA) → OUT DARI AKUN FROM
        if ($biaya_admin > 0) {
            $this->trx->insert([
                'user_id'   => $uid,
                'tanggal'   => $tanggal,
                'jenis'     => 'out',
                'sumber_id' => $from,
                'tujuan_id' => null,
                'kategori'  => 'Biaya Admin',
                'deskripsi' => 'Biaya admin transfer',
                'jumlah'    => $biaya_admin,
            ]);
        }

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

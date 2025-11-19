<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\TransactionCategoryModel;

class Dashboard extends BaseController
{
    protected $transaksiModel;
    protected $categoryModel;  // 👈 tambah ini

    public function __construct()
    {
        helper(['auth']);
        $this->transaksiModel = new TransaksiModel();
        $this->categoryModel  = new TransactionCategoryModel(); // 👈 inisialisasi
    }

    // ============================
    // 🔹 API Data Keuangan + Kategori (PER BULAN)
    // ============================
    public function getFinanceData()
    {
        $userId = user_id();
        $month  = $this->request->getGet('month') ?? date('m');
        $year   = $this->request->getGet('year') ?? date('Y');

        // Map kategori → icon dari tabel transaction_categories
        $cats = $this->categoryModel->findAll();
        $iconMap = [
            'in'  => [],
            'out' => [],
        ];
        foreach ($cats as $c) {
            $type = $c['type']; // in | out
            $name = $c['name'];
            $iconMap[$type][$name] = $c['icon'] ?: null;
        }

        // Ambil total per kategori (kecuali Transfer)
        $rows = $this->transaksiModel
            ->select('kategori, jenis, SUM(jumlah) as total')
            ->where('user_id', $userId)
            ->where('MONTH(tanggal)', $month)
            ->where('YEAR(tanggal)', $year)
            ->where('kategori IS NOT NULL')
            ->where('kategori !=', '')
            ->where('kategori !=', 'Transfer') // ❌ exclude Transfer
            ->groupBy('kategori, jenis')
            ->findAll();

        $totalIn  = 0;
        $totalOut = 0;

        $pemasukan  = [];
        $pengeluaran = [];

        foreach ($rows as $r) {
            $jenis    = $r['jenis'];      // in | out | (kalau ada 'pemasukan'/'pengeluaran' kita anggap in/out)
            $kategori = $r['kategori'] ?: 'Lain-lain';
            $total    = (float) $r['total'];

            // Normalisasi jenis
            if ($jenis === 'pemasukan')  $jenis = 'in';
            if ($jenis === 'pengeluaran') $jenis = 'out';

            if ($jenis === 'in') {
                $totalIn += $total;
                $pemasukan[] = [
                    'kategori' => $kategori,
                    'total'    => $total,
                    'icon'     => $iconMap['in'][$kategori] ?? 'fas fa-sign-in-alt',
                ];
            } elseif ($jenis === 'out') {
                $totalOut += $total;
                $pengeluaran[] = [
                    'kategori' => $kategori,
                    'total'    => $total,
                    'icon'     => $iconMap['out'][$kategori] ?? 'fas fa-sign-out-alt',
                ];
            }
        }

        // Urutkan dari terbesar → kecil
        usort($pengeluaran, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });
        usort($pemasukan, function ($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        return $this->response->setJSON([
            'total_in'      => $totalIn,
            'total_out'     => $totalOut,
            'pengeluaran'   => $pengeluaran,
            'pemasukan'     => $pemasukan,
        ]);
    }
}

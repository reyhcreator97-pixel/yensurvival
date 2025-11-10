<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    protected $transaksiModel;

    public function __construct()
    {
        helper(['auth']);
        $this->transaksiModel = new TransaksiModel();
    }

    // ============================
    // 🔹 API Chart Data
    // ============================
    public function getChartData()
    {
        $userId = user_id();
        $month = $this->request->getGet('month') ?? date('m');
        $year = $this->request->getGet('year') ?? date('Y');

        $data = $this->transaksiModel
            ->select('tanggal, jenis, SUM(jumlah) as total')
            ->where('user_id', $userId)
            ->where('MONTH(tanggal)', $month)
            ->where('YEAR(tanggal)', $year)
            ->groupBy('tanggal, jenis')
            ->orderBy('tanggal', 'ASC')
            ->findAll();

        // Format data untuk chart
        $labels = [];
        $pemasukan = [];
        $pengeluaran = [];

        foreach ($data as $row) {
            $tanggal = date('d M', strtotime($row['tanggal']));
            if (!in_array($tanggal, $labels)) {
                $labels[] = $tanggal;
            }
        }

        foreach ($labels as $tgl) {
            $pemasukan[] = 0;
            $pengeluaran[] = 0;
        }

        foreach ($data as $row) {
            $tanggal = date('d M', strtotime($row['tanggal']));
            $idx = array_search($tanggal, $labels);
            if ($idx !== false) {
                if ($row['jenis'] == 'in') {
                    $pemasukan[$idx] = (float) $row['total'];
                } else {
                    $pengeluaran[$idx] = (float) $row['total'];
                }
            }
        }

        return $this->response->setJSON([
            'labels' => $labels,
            'pemasukan' => $pemasukan,
            'pengeluaran' => $pengeluaran
        ]);
    }
}

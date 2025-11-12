<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\DevelopmentLogModel;

class Development extends BaseController
{
    protected $devLog;

    public function __construct()
    {
        $this->devLog = new DevelopmentLogModel();
    }

    public function index()
    {
        $perPage = 5; // tampil 5 versi per halaman
        $page    = $this->request->getVar('page') ?? 1;

        // ambil semua log, urut dari tanggal terbaru dan status
        $logs = $this->devLog
            ->orderBy('date', 'DESC')
            ->orderBy('status', 'ASC')
            ->paginate($perPage);

        $pager = $this->devLog->pager;

        // kelompokkan seperti sebelumnya
        $grouped = [];
        foreach ($logs as $r) {
            $key = $r['version'] . '|' . date('Y-m-d', strtotime($r['date'])) . '|' . $r['status'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'version_label' => $r['version'],
                    'date'   => $r['date'],
                    'status' => $r['status'],
                    'items'  => []
                ];
            }
            $grouped[$key]['items'][] = $r;
        }

        $data = [
            'title'    => 'Development',
            'versions' => $grouped,
            'pager'    => $pager,
        ];

        return view('user/development/index', $data);
    }
}

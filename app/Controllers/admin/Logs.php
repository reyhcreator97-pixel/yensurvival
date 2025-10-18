<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LogModel;
use Myth\Auth\Models\UserModel;

class Logs extends BaseController
{
    protected $log;
    protected $user;

    public function __construct()
    {
        $this->log  = new LogModel();
        $this->user = new UserModel();
    }

    public function index()
    {
        // Pagination setup
        $perPage = 10;
        $page = $this->request->getVar('page_logs') ?? 1;

        // Ambil data log urut terbaru
        $logs = $this->log
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage, 'logs');

        $data = [
            'title' => 'Log Aktivitas',
            'logs'  => $logs,
            'pager' => $this->log->pager
        ];

        return view('admin/logs', $data);
    }
}
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
        $perPage  = 10;
        $action   = $this->request->getGet('action');
        $tanggal  = $this->request->getGet('tanggal');
    
        // builder dasar + urutan terbaru dulu
        $builder = $this->log->orderBy('created_at', 'DESC');
    
        // filter
        if (!empty($action)) {
            $builder->where('action', $action);
        }
        if (!empty($tanggal)) {
            // created_at bertipe DATETIME
            $builder->where('DATE(created_at)', $tanggal);
        }
    
        // ambil data pakai builder yang sudah difilter
        $logs  = $builder->paginate($perPage, 'logs');
        $pager = $this->log->pager;
    
        // daftar aksi untuk dropdown
        $actions = $this->log->select('action')
                             ->distinct()
                             ->orderBy('action', 'ASC')
                             ->findAll();
    
        return view('admin/logs', [
            'title'   => 'Log Aktivitas',
            'logs'    => $logs,
            'pager'   => $pager,
            'actions' => $actions,   // untuk dropdown
            'action'  => $action,    // nilai terpilih (keep selected)
            'tanggal' => $tanggal,   // nilai terpilih (keep value)
        ]);
    }
}
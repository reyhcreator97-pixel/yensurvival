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
        $this->log = new LogModel();
        $this->user = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Log Aktivitas',
            'logs'  => $this->log->orderBy('created_at', 'DESC')->findAll(100) // tampilkan 100 terakhir
        ];

        return view('admin/logs', $data);
    }
}
<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SubscriptionModel;
use App\Models\TransaksiModel;

class Dashboard extends BaseController
{
    protected $userModel;
    protected $subModel;
    protected $trxModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->subModel  = new SubscriptionModel();
        $this->trxModel  = new TransaksiModel();
    }

    public function index()
    {
        // Dummy data dulu, nanti real dari DB
        $data = [
            'title' => 'Dashboard Admin',
            'totalUser' => $this->userModel->countAllResults(),
            'totalSub'  => $this->subModel->countAllResults(),
            'totalTrx'  => $this->trxModel->countAllResults(),
        ];

        return view('admin/dashboard', $data);
    }
}

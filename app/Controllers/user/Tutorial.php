<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\TutorialVideoModel;

class Tutorial extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new TutorialVideoModel();
    }

    public function index()
    {
        // 🔹 Ambil semua video tutorial dari database
        $videos = $this->model->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title'  => 'Video Tutorial',
            'videos' => $videos
        ];

        return view('user/tutorial/index', $data);
    }
}

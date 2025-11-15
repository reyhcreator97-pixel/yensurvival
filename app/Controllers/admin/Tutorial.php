<?php

namespace App\Controllers\Admin;

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
        $data = [
            'title' => 'Tutorial Videos',
            'videos' => $this->model->orderBy('created_at', 'DESC')->findAll(),
        ];
        return view('admin/tutorial/index', $data);
    }

    public function store()
    {
        $this->model->insert([
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'video_url'   => $this->request->getPost('video_url'),
            'category'    => $this->request->getPost('category'),
        ]);
        return redirect()->back()->with('message', 'Video tutorial berhasil ditambahkan.');
    }

    public function update($id)
    {
        $this->model->update($id, [
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
            'video_url'   => $this->request->getPost('video_url'),
            'category'    => $this->request->getPost('category'),
        ]);
        return redirect()->back()->with('message', 'Video tutorial berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->back()->with('message', 'Video tutorial berhasil dihapus.');
    }
}

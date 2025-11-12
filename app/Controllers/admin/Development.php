<?php

namespace App\Controllers\Admin;

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
        $data = [
            'title' => 'Development Logs',
            'logs'  => $this->devLog->orderBy('date', 'DESC')->findAll(),
        ];
        return view('admin/development/index', $data);
    }

    public function save()
    {
        $this->devLog->save([
            'id'          => $this->request->getPost('id'),
            'version'     => $this->request->getPost('version'),
            'date'        => $this->request->getPost('date'),
            'status'      => $this->request->getPost('status'),
            'section'     => $this->request->getPost('section'),
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to('/admin/development')->with('message', 'Update changelog berhasil disimpan!');
    }

    public function edit($id)
    {
        $log = $this->devLog->find($id);

        if (!$log) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Development Log',
            'log'   => $log,
        ];

        return view('admin/development/edit', $data);
    }

    public function update($id)
    {
        $log = $this->devLog->find($id);
        if (!$log) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'version'     => $this->request->getPost('version'),
            'date'        => $this->request->getPost('date'),
            'status'      => $this->request->getPost('status'),
            'section'     => $this->request->getPost('section'),
            'title'       => $this->request->getPost('title'),
            'description' => $this->request->getPost('description'),
        ];

        $this->devLog->update($id, $data);
        return redirect()->to('/admin/development')->with('message', 'Log berhasil diperbarui.');
    }


    public function delete($id)
    {
        $this->devLog->delete($id);
        return redirect()->to('/admin/development')->with('message', 'Data berhasil dihapus.');
    }
}

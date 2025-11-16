<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionCategoryModel;

class TransactionCategory extends BaseController
{
    protected $cat;

    public function __construct()
    {
        $this->cat = new TransactionCategoryModel();
    }

    public function index()
    {
        return view('admin/transaksi_kategori', [
            'title' => 'Kategori Transaksi',
            'list'  => $this->cat->orderBy('type', 'ASC')->findAll()
        ]);
    }

    public function save()
    {
        $this->cat->insert([
            'name' => $this->request->getPost('name'),
            'icon' => $this->request->getPost('icon'),
            'type' => $this->request->getPost('type'),
        ]);

        return redirect()->back()->with('message', 'Kategori dibuat.');
    }

    public function update($id)
    {
        $this->cat->update($id, [
            'name' => $this->request->getPost('name'),
            'icon' => $this->request->getPost('icon'),
            'type' => $this->request->getPost('type'),
        ]);

        return redirect()->back()->with('message', 'Kategori diubah.');
    }

    public function delete($id)
    {
        $this->cat->delete($id);

        return redirect()->back()->with('message', 'Kategori dihapus.');
    }
}

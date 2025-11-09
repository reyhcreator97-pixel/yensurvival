<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CouponModel;

class Coupons extends BaseController
{
    protected $couponModel;

    public function __construct()
    {
        $this->couponModel = new CouponModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kelola Kupon Promo',
            'coupons' => $this->couponModel->orderBy('id', 'DESC')->findAll()
        ];
        return view('admin/coupons', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Kupon Baru';
        return view('admin/coupon_form', $data);
    }

    public function store()
    {
        $this->couponModel->save([
            'kode' => strtoupper($this->request->getPost('kode')),
            'jenis' => $this->request->getPost('jenis'),
            'nilai' => $this->request->getPost('nilai'),
            'keterangan' => $this->request->getPost('keterangan'),
            'berlaku_mulai' => $this->request->getPost('berlaku_mulai'),
            'berlaku_sampai' => $this->request->getPost('berlaku_sampai'),
            'max_usage' => $this->request->getPost('max_usage'),
            'status' => $this->request->getPost('status')
        ]);

        return redirect()->to('/admin/coupons')->with('message', 'Kupon berhasil disimpan!');
    }

    public function delete($id)
    {
        $this->couponModel->delete($id);
        return redirect()->to('/admin/coupons')->with('message', 'Kupon berhasil dihapus!');
    }
}

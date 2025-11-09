<?php

namespace App\Controllers;

use App\Models\CouponModel;

class Coupon extends BaseController
{
    protected $couponModel;

    public function __construct()
    {
        $this->couponModel = new CouponModel();
    }

    public function check()
    {
        $code = $this->request->getGet('code');
        $coupon = $this->couponModel
            ->where('kode', $code)
            ->where('status', 'active')
            ->where('berlaku_mulai <=', date('Y-m-d'))
            ->where('berlaku_sampai >=', date('Y-m-d'))
            ->first();

        if (!$coupon) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Kupon tidak ditemukan atau sudah tidak aktif.']);
        }

        if ($coupon['max_usage'] > 0 && $coupon['used_count'] >= $coupon['max_usage']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kupon sudah mencapai batas penggunaan (' . $coupon['used_count'] . '/' . $coupon['max_usage'] . ').'
            ]);
        }

        $label = $coupon['jenis'] === 'percent'
            ? $coupon['nilai'] . '%'
            : 'Rp ' . number_format($coupon['nilai'], 0, ',', '.');

        return $this->response->setJSON([
            'status' => 'success',
            'jenis'  => $coupon['jenis'],
            'nilai'  => $coupon['nilai'],
            'label'  => $label,
            'used_count' => $coupon['used_count'],
            'max_usage'  => $coupon['max_usage']
        ]);
    }
}

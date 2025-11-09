<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
    protected $table = 'coupons';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'kode',
        'jenis',
        'nilai',
        'keterangan',
        'berlaku_mulai',
        'berlaku_sampai',
        'max_usage',
        'used_count',
        'status',
        'created_at'
    ];
    protected $useTimestamps = false;
}

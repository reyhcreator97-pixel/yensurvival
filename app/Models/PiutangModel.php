<?php
namespace App\Models;
use CodeIgniter\Model;

class PiutangModel extends Model
{
    protected $table = 'piutang';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'akun_id',
        'tanggal',
        'nama',
        'keterangan',
        'jumlah',
        'dibayar',
        'status'
    ];
}

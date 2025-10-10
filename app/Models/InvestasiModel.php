<?php
namespace App\Models;

use CodeIgniter\Model;

class InvestasiModel extends Model
{
    protected $table            = 'investasi';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'user_id',
        'tanggal',
        'nama',
        'akun_id',
        'jumlah',
        'nilai_sekarang',
        'deskripsi',
        'status',
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
}

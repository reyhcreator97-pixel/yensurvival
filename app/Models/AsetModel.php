<?php

namespace App\Models;

use CodeIgniter\Model;

class AsetModel extends Model
{
    protected $table = 'aset_items';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'tanggal',
        'nama',
        'akun_id',
        'jumlah',
        'nilai_sekarang',
        'deskripsi',
        'status',
        'created_at',
        'updated_at'
    ];

    // Ambil semua aset milik user
    public function getAllByUser($user_id)
    {
        return $this->where('user_id', $user_id)
                    ->orderBy('id', 'DESC')
                    ->findAll();
    }

    // Hitung total nilai aset aktif
    public function getTotalAset($user_id)
    {
        return (float)($this->where(['user_id' => $user_id, 'status' => 'aktif'])
            ->selectSum('nilai_sekarang')
            ->first()['nilai_sekarang'] ?? 0);
    }
}

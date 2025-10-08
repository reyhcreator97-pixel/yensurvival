<?php
namespace App\Models;

use CodeIgniter\Model;

class UtangModel extends Model
{
    protected $table         = 'utang';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'user_id',
        'tanggal',
        'nama',
        'keterangan',
        'jumlah',
        'status', // 'belum' atau 'lunas'
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;

    // 🔹 Biar gampang ambil total per user
    public function getTotalByUser($user_id): array
    {
        $utang = $this->where(['user_id'=>$user_id, 'status'=>'belum'])
                      ->selectSum('jumlah')->first()['jumlah'] ?? 0;
        $lunas = $this->where(['user_id'=>$user_id, 'status'=>'lunas'])
                      ->selectSum('jumlah')->first()['jumlah'] ?? 0;

        return [
            'utang' => (float)$utang,
            'lunas' => (float)$lunas,
            'sisa'  => max(0, $utang - $lunas)
        ];
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class KekayaanItemModel extends Model
{
    protected $table         = 'kekayaan_items';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id', 'kategori', 'deskripsi', 'jumlah' , 'saldo_terkini'
    ];

    public function byUserGrouped(int $userId): array
    {
        $rows = $this->where('user_id', $userId)
                     ->orderBy('kategori', 'ASC')
                     ->orderBy('id', 'ASC')
                     ->findAll();

        $out = ['uang'=>[], 'utang'=>[], 'piutang'=>[], 'aset'=>[], 'investasi'=>[]];
        foreach ($rows as $r) $out[$r['kategori']][] = $r;
        return $out;
    }

    public function totalByUser(int $userId): array
    {
        $tot = ['uang'=>0, 'utang'=>0, 'piutang'=>0, 'aset'=>0, 'investasi'=>0];
        $rows = $this->select('kategori, SUM(jumlah) as total')
                     ->where('user_id', $userId)
                     ->groupBy('kategori')
                     ->findAll();
        foreach ($rows as $r) $tot[$r['kategori']] = (float)$r['total'];
        return $tot;
    }
}

<?php
namespace App\Models;

use CodeIgniter\Model;

class InvestasiModel extends Model
{
    protected $table         = 'investasi';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'user_id',      // pemilik
        'nama',         // nama aset: Saham BBCA / RDPU / Emas Antam, dll
        'kategori',     // saham | reksadana | emas | lain
        'qty',          // jumlah unit/gram/lot (bebas)
        'harga',        // harga per unit saat beli
        'biaya',        // biaya lain-lain saat beli
        'nilai_total',  // qty*harga + biaya
        'akun_beli_id', // sumber dana saat beli
        'tanggal_beli',
        'status',       // aktif|terjual
        'tanggal_jual', // jika dijual
        'akun_jual_id', // akun penerima saat jual
        'nilai_jual',   // total nilai jual (gross)
        'catatan',
    ];
    protected $useTimestamps = false;
}
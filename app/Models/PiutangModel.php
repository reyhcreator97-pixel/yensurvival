<?php
namespace App\Models;
use CodeIgniter\Model;

class PiutangModel extends Model
{
    protected $table = 'piutang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id','nama','jumlah','dibayar','akun_id','keterangan'];
}

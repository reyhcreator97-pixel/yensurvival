<?php
namespace App\Models;
use CodeIgniter\Model;

class UtangModel extends Model
{
    protected $table = 'utang';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id','nama','jumlah','dibayar','akun_id','keterangan'];
}

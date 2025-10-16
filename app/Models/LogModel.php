<?php
namespace App\Models;
use CodeIgniter\Model;

class LogModel extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'role',
        'action',
        'description',
        'ip_address',
        'user_agent',
        'created_at'
    ];
    protected $useTimestamps = false;
}
<?php
namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'email',
        'username',
        'password_hash',
        'active',
        'created_at',
        'update_at',
    ];
    protected $useTimestamps    = true;
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class DevelopmentLogModel extends Model
{
    protected $table = 'development_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'version',
        'date',
        'status',
        'section',
        'title',
        'description',
        'created_at',
        'updated_at'
    ];
    protected $useTimestamps = true;
}

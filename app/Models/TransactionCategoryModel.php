<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionCategoryModel extends Model
{
    protected $table      = 'transaction_categories';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'name',
        'icon',
        'type'
    ];
}

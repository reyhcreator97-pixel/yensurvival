<?php
namespace App\Models;
use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'currency',
        'price_monthly',
        'price_yearly',
        'backup_schedule',
        'contact_whatsapp'
    ];
}
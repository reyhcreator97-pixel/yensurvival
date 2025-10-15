<?php
namespace App\Models;

use CodeIgniter\Model;

class SubscriptionModel extends Model
{
    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'plan_type',
        'status',
        'start_date',
        'end_date',
        'created_at',
        'updated_at'
    ];

    // optional: auto check expired
    public function checkExpired()
    {
        $today = date('Y-m-d');
        return $this->where('end_date <', $today)
                    ->where('status', 'active')
                    ->set(['status' => 'expired'])
                    ->update();
    }
}
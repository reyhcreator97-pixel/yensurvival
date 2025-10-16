<?php
use App\Models\LogModel;

if (!function_exists('log_activity')) {
    function log_activity($action, $description = null)
    {
        $userId = user_id() ?? 0;

        // deteksi role via tabel Myth/Auth
        $role = 'unknown';
        if ($userId) {
            $db = \Config\Database::connect();
            $builder = $db->table('auth_groups_users')
                          ->select('auth_groups.name as role')
                          ->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id')
                          ->where('auth_groups_users.user_id', $userId)
                          ->get()
                          ->getRow();

            if ($builder) {
                $role = $builder->role;
            }
        }

        $data = [
            'user_id'    => $userId,
            'role'       => $role,
            'action'     => $action,
            'description'=> $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'CLI',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $db->table('logs')->insert($data);
    }
}
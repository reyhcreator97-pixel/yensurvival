<?php

use Config\Database;

if (!function_exists('log_activity')) {
    function log_activity($action, $description = null)
    {
        $userId = user_id() ?? 0;
        $role   = 'unknown';

        // Deteksi role via tabel Myth/Auth
        if ($userId) {
            $db = Database::connect();
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

        // Ambil IP address
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';

        // Ambil User Agent
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? '-';

        // Deteksi Browser
        $browser = 'Unknown';
        if (preg_match('/Chrome/i', $agent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/Firefox/i', $agent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/Safari/i', $agent) && !preg_match('/Chrome/i', $agent)) {
            $browser = 'Safari';
        } elseif (preg_match('/Edge/i', $agent)) {
            $browser = 'Edge';
        } elseif (preg_match('/OPR|Opera/i', $agent)) {
            $browser = 'Opera';
        }

        // Deteksi Sistem Operasi
        $os = 'Unknown OS';
        if (preg_match('/Windows/i', $agent)) {
            $os = 'Windows';
        } elseif (preg_match('/Mac/i', $agent)) {
            $os = 'MacOS';
        } elseif (preg_match('/Linux/i', $agent)) {
            $os = 'Linux';
        } elseif (preg_match('/Android/i', $agent)) {
            $os = 'Android';
        } elseif (preg_match('/iPhone|iPad/i', $agent)) {
            $os = 'iOS';
        }

        // Format hasil user agent yang ringkas
        $userAgent = "{$browser} on {$os}";

        // Simpan ke database
        $db = Database::connect();
        $data = [
            'user_id'    => $userId,
            'role'       => $role,
            'action'     => $action,
            'description'=> $description,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $db->table('logs')->insert($data);
    }
}
<?php

use CodeIgniter\Events\Events;
use App\Helpers\log_helper;

// Pastikan helper log dipanggil
helper('log');

// Event login berhasil
Events::on('login', function($user) {
    log_activity('Login', 'User ' . ($user->username ?? 'Unknown') . ' berhasil login.');
});

// Event logout
Events::on('logout', function($user) {
    log_activity('Logout', 'User ' . ($user->username ?? 'Unknown') . ' melakukan logout.');
});
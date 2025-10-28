<?php

namespace App\Controllers\Auth;

use Myth\Auth\Controllers\AuthController as BaseAuthController;

class Login extends BaseAuthController
{
    protected function _loginRedirect(): string
    {
        // Hapus session redirect bawaan Myth\Auth
        if (session()->has('redirect_url')) {
            session()->remove('redirect_url');
        }

        // Ambil user saat ini
        $user = auth()->user();

        // Cek role user
        if ($user->inGroup('admin')) {
            return '/admin/dashboard';
        } elseif ($user->inGroup('user')) {
            return '/user/index';
        }

        // Default fallback
        return '/';
    }
    
}
<?php

namespace App\Controllers;
helper('auth');
class Home extends BaseController
{
    public function index()
    {
        if (!logged_in()) {
            return redirect()->to('auth/login');
        }
        if (in_groups('Admin')) {
            return redirect()->to('/admin/index');
        }
        return redirect()->to('/user/index');
    }
    public function register(): string
    {
        return view('auth/register');
    }
    public function user(): string
    {
        return view('user/index');
    }
 
}

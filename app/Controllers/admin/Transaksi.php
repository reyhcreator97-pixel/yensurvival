<?php

namespace App\Controllers;

class Transaksi extends BaseController
{
    public function index(): string
    {
        $data['title']='Transaksi';
        return view('admin/transaksi', $data);
    }

  
 
}

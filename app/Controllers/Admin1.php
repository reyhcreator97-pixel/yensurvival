<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function index(): string
    {
        $data['title']='User List';
        return view('admin/index', $data);
    }

    public function dashboard(): string
    {
         // --- Ambil data harga 1 gram dari IndoGold ---
         $indogold = new \App\Controllers\EmasIndogold();
         $json = $indogold->getHarga1Gram(); // fungsi khusus yang kita bikin di bawah
         
        $data = [
            'title'   => 'Dashboard',
            'harga1g' => $json,  // harga 1 gram (Antam & UBS)
        ];
        return view('admin/dashboard', $data);
    }
  
 
}

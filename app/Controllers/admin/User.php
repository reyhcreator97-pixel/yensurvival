<?php

namespace App\Controllers;

class User extends BaseController
{
    public function index(): string
    {
        $data['title']='User List';
        return view('admin/user', $data);
    }

  
 
}

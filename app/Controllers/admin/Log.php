<?php

namespace App\Controllers;

class Log extends BaseController
{
    public function index(): string
    {
        $data['title']='Log';
        return view('admin/log', $data);
    }

  
 
}

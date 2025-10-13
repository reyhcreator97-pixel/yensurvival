<?php

namespace App\Controllers;

class Index extends BaseController
{
    public function index(): string
    {
        $data['title']='Welcome';
        return view('admin/index', $data);
    }

  
 
}

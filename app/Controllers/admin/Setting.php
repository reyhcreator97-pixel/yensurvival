<?php

namespace App\Controllers;

class Setting extends BaseController
{
    public function index(): string
    {
        $data['title']='Setting';
        return view('admin/settting', $data);
    }

  
 
}

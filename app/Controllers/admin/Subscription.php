<?php

namespace App\Controllers;

class Susbcription extends BaseController
{
    public function index(): string
    {
        $data['title']='Subscription';
        return view('admin/subscription', $data);
    }

  
 
}

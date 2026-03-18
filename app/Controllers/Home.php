<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function dbtest()
    {
    $db = \Config\Database::connect();
    echo "Database connected";
    }
    
    public function index(): string
    {
        return view('welcome_message');
    }
}

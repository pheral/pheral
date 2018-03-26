<?php

namespace App\Controllers;

use Pheral\Essential\Main\Controller;

class Help extends Controller
{
    public function index($page = 1)
    {
        return view('help.index', [
            'page' => (int)$page,
        ]);
    }
}

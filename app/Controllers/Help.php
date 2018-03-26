<?php

namespace App\Controllers;

use Pheral\Essential\Main\Controller;
use Pheral\Essential\Network\Request;

class Help extends Controller
{
    public function index(Request $request, $page = 1)
    {
        return view('help.index', [
            'page' => (int)$page,
            'isAjax' => (int)$request->isAjax()
        ]);
    }
}

<?php

namespace App\Controllers;

use Pheral\Essential\Layers\Controller;
use Pheral\Essential\Network\Frame;

class Help extends Controller
{
    public function index(Frame $frame, $page = 1)
    {
        return view('help.index', [
            'page' => (int)$page,
            'isAjax' => (int)$frame->isAjaxRequest()
        ]);
    }
}

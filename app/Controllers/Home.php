<?php

namespace App\Controllers;

use App\Controllers\Abstracts\Controller;
use Pheral\Essential\Layers\View;

class Home extends Controller
{
    public function index()
    {
        return $this->render([
            'content' => View::make('templates.home.index')
        ]);
    }
}

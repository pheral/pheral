<?php

namespace App\Controllers;

use App\Controllers\Abstracts\Controller;

class Home extends Controller
{
    public function index()
    {
        return $this->render([
            'content' => view('templates.home.index')
        ]);
    }
}

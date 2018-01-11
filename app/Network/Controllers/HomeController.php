<?php

namespace App\Network\Controllers;


use Pheral\Essential\Direct\Controller;
use Pheral\Essential\Direct\View;

class HomeController extends Controller
{
    public function index()
    {
        return View::make('home.index')->render(['version' => 'v1.0.2']);
    }
}
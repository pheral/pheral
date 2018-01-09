<?php

namespace App\Network\Controllers;


class HomeController
{
    public function index()
    {
        app()->force('templates/home/index.php');
    }
}
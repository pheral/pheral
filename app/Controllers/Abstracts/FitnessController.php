<?php

namespace App\Controllers\Abstracts;

use App\Models\Fitness\Auth;

abstract class FitnessController extends Controller
{
    protected $path = 'layouts.fitness.application';
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
    }
}

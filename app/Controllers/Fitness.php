<?php

namespace App\Controllers;

use App\Controllers\Abstracts\Controller;

class Fitness extends Controller
{
    protected $path = 'layouts.fitness';
    public function index()
    {
        return $this->render([
            'content' => view('templates.fitness.index', [
                'practices' => (new \App\Models\Fitness())->getPractices(),
            ])
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Controllers\Abstracts\Controller;

class Fitness extends Controller
{
    protected $path = 'layouts.fitness';
    protected $fitness;
    public function __construct()
    {
        $this->fitness = new \App\Models\Fitness();
    }

    public function index()
    {
        $user = $this->fitness->getUser();
        $practices = $this->fitness->getPractices($user);

        // profiler()->database()->debug();

        return $this->render([
            'content' => view('templates.fitness.index', [
                'practices' => $practices,
            ])
        ]);
    }
}

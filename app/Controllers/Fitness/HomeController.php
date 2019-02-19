<?php

namespace App\Controllers\Fitness;

use App\Controllers\Abstracts\FitnessController;
use App\Models\Fitness\Home;

class HomeController extends FitnessController
{
    protected $home;

    public function __construct()
    {
        parent::__construct();
        $this->home = new Home();
    }

    public function index()
    {
        $user = $this->auth->getUser();
        $practices = $this->home->getPractices($user);
        return $this->render([
            'content' => view('templates.fitness.home.index', [
                'practices' => $practices,
            ])
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Controllers\Abstracts\Controller;

class Reports extends Controller
{
    public function index()
    {
        return $this->render([
            'content' => view('templates.reports.index', [
                'tasks' => (new \App\Models\Reports())->getTasks(),
            ])
        ]);
    }
}

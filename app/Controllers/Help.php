<?php

namespace App\Controllers;

use App\Controllers\Abstracts\Controller;
use Pheral\Essential\Network\Frame;

class Help extends Controller
{
    public function index($param = 'default')
    {
        $frame = Frame::instance();

        $model = new \App\Models\Example();

        $content = view('templates.help.index', [
            'paramArgument' => $param,
            'paramRequest' => $frame->request()->get('param'),
            'dbExampleAdd' => [
                'firstTestId' => $model->addTest('first'),
                'secondTestId' => $model->addTest('second')
            ],
            'dbExampleGet' => $model->getTest(),
        ]);

        return $this->render([
            'content' => $content
        ]);
    }
}

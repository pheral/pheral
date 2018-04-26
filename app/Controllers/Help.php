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
        $firstTestId = $model->addTest('first');
        $secondTestId = $model->addTest('second');
        $content = view('templates.help.index', [
            'paramArgument' => $param,
            'paramRequest' => $frame->request()->get('param'),
            'dbExampleAdd' => [
                'firstTestId' => $firstTestId,
                'secondTestId' => $secondTestId
            ],
            'dbExampleGet' => $model->getTest($firstTestId, $secondTestId),
        ]);

        return $this->render([
            'content' => $content
        ]);
    }
}

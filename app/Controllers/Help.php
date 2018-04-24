<?php

namespace App\Controllers;

use App\Controllers\Abstracts\Controller;
use Pheral\Essential\Network\Frame;

class Help extends Controller
{
    public function index(Frame $frame, $param = 'default')
    {
        $content = view('templates.help.index', [
            'paramArgument' => $param,
            'paramRequest' => $frame->request()->get('param'),
//            'dbExample' => (new \App\Models\Example())->getTest(),
        ]);

        return $this->render([
            'content' => $content
        ]);
    }
}

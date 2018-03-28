<?php

namespace App\Controllers;

use Pheral\Essential\Network\Frame;

class Help extends Front
{
    public function index(Frame $frame, $param = 'default')
    {
        $content = view('templates.help.index', [
            'paramArgument' => $param,
            'paramRequest' => $frame->request()->get('param')
        ]);

        return $this->render([
            'content' => $content
        ]);
    }
}

<?php

namespace App\Controllers;

use App\Models\Test;
use Pheral\Essential\Network\Frame;

class Help extends Front
{
    public function index(Frame $frame, $param = 'default')
    {
        $content = view('templates.help.index', [
            'paramArgument' => $param,
            'paramRequest' => $frame->request()->get('param'),
            'dbExample' => (new Test())->get(),
        ]);

        return $this->render([
            'content' => $content
        ]);
    }
}

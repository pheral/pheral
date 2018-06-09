<?php

namespace App\Controllers;

use App\Controllers\Abstracts\Controller;
use Pheral\Essential\Layers\View;
use Pheral\Essential\Network\Frame;

class Example extends Controller
{
    public function index($param = 'default')
    {
        $frame = Frame::instance();

        $content = View::make('templates.help.index', [
            'paramArgument' => $param,
            'paramRequest' => $frame->request()->get('param'),
            'dbExample' => (new \App\Models\Example())->test()
        ]);

        return $this->render([
            'content' => $content
        ]);
    }
}

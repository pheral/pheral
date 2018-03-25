<?php

namespace App\Controllers;

use Pheral\Essential\Network\Request;
use Pheral\Essential\Tools\View;

class Help
{
    public function index(Request $request, $page = null)
    {
        $view = new View('help.index', [
            'page' => $page,
        ]);
        if ($request->files()->all()) {
            debug([
                'page' => $page,
                'server' => server()->all(),
                'session' => session()->all(),
                'cookies' => cookies()->all(),
                'request' => [
                    'inputs' => $request->all(),
                    'files' => $request->files()->all(),
                    'headers' => $request->headers()->all(),
                ],
            ]);
        }
        return $view;
    }
    public function about(Request $request, $first, $middle = null, $last = null, $extra = null)
    {
        if ($request->has('redirect')) {
            return redirect()->back();
        }
        return new View('help.about', [
            'content' => 'test',
            'first' => $first,
            'middle' => $middle,
            'last' => $last,
            'extra' => $extra,
        ]);
    }
}

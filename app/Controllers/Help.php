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
            debug($request);
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

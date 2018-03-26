<?php

namespace App\Controllers;

use Pheral\Essential\Network\Request;

class Help
{
    public function index(Request $request, $page = null)
    {
        if ($request->files()->all()) {
            debug($request);
        }
        return view('help.index', [
            'page' => $page,
        ]);
    }
    public function about(Request $request, $first, $middle = null, $last = null, $extra = null)
    {
        if ($request->has('redirect')) {
            return redirect()->back();
        }
        return view('help.about', [
            'content' => 'test',
            'first' => $first,
            'middle' => $middle,
            'last' => $last,
            'extra' => $extra,
        ]);
    }
}

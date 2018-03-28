<?php

namespace Pheral\Essential\Layers;

abstract class Controller
{
    protected $path = '';
    public function error404($message = '')
    {
        error404($message);
    }
    public function render($data = [], $path = '')
    {
        $path = $path ? $path : $this->path;
        return view($path, $data);
    }
    public function isAjax()
    {
        return frame()->isAjaxRequest();
    }
    public function ajaxRequired()
    {
        if ($this->isAjax()) {
            $this->error404('Ajax required');
        }
    }
}

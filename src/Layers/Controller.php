<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Network\Frame;

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
        return View::make($path, $data);
    }
    public function isAjax()
    {
        return Frame::instance()->isAjaxRequest();
    }
    public function ajaxRequired()
    {
        if ($this->isAjax()) {
            $this->error404('Ajax required');
        }
    }
}

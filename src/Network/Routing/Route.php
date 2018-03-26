<?php

namespace Pheral\Essential\Network\Routing;

class Route
{
    protected $controller;
    protected $action;
    protected $params = [];
    protected $method;
    public function __construct($controller = '', $action = '', $params = [], $method = '')
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
        $this->method = $method;
    }
    public function controller()
    {
        return $this->controller;
    }
    public function action()
    {
        return $this->action;
    }
    public function method()
    {
        return $this->method;
    }
    public function params()
    {
        return $this->params;
    }
    public static function make($options = []): Route
    {
        return new static(
            array_get($options, 'controller'),
            array_get($options, 'action', 'index'),
            array_get($options, 'params', []),
            array_get($options, 'method')
        );
    }
}

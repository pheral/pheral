<?php

namespace Pheral\Essential\Network\Routing;

class Route
{
    protected $controller;
    protected $action;
    protected $params = [];
    protected $method;
    protected $wrappers = [];
    public function __construct($controller = '', $action = '', $params = [], $method = '', $wrappers = [])
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
        $this->method = $method;
        $this->wrappers = $wrappers;
    }
    public static function make($options = []): Route
    {
        $route = new static(
            array_get($options, 'controller'),
            array_get($options, 'action', 'index'),
            array_get($options, 'params', []),
            array_get($options, 'method'),
            array_get($options, 'wrappers', [])
        );
        return $route;
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
    public function wrappers()
    {
        return $this->wrappers;
    }
    public function get($key, $default = null)
    {
        return array_get($this->params, $key, $default);
    }
}

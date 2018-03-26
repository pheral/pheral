<?php

namespace Pheral\Essential\Network;

use Pheral\Essential\Container\Factory;

class Core
{
    protected $request;
    protected $controller;
    protected $action;
    protected $params;
    protected $redirect;
    protected $response;
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function handle(): Response
    {
        $controller = $this->getController();
        $action = $this->getAction();
        $data = null;
        if ($controller && $action) {
            $data = $action->invokeArgs($controller, $this->getParams());
        }
        if ($data instanceof Response) {
            $response = $data;
        } else {
            $response = new Response($data);
        }
        return $response;
    }
    protected function getController()
    {
        if (isset($this->controller)) {
            return $this->controller;
        }
        if (!$route = $this->request->route()) {
            return null;
        }
        if (!$controller = $route->controller()) {
            return null;
        }
        if (is_null($this->controller)) {
            $controllerClass = Factory::make($controller);
            $this->controller = $controllerClass ?? false;
        }
        return $this->controller;
    }
    protected function getAction()
    {
        if (isset($this->action)) {
            return $this->action;
        }
        if (!$route = $this->request->route()) {
            return null;
        }
        if (!$action = $route->action()) {
            return null;
        }
        if (!$controller = $this->getController()) {
            return null;
        }
        if (is_null($this->action)) {
            try {
                $reflection = new \ReflectionMethod($controller, $action);
            } catch (\Throwable $exception) {
                debug([
                    'DEBUG' => $exception->getMessage(),
                    'PLACE' => $exception->getFile() . ':' . $exception->getLine(),
                    'TRACE' => $exception->getTraceAsString()
                ]);
                return null;
            }
            $this->action = $reflection->isPublic() ? $reflection : false;
        }
        return $this->action;
    }
    protected function getParams()
    {
        if (isset($this->params)) {
            return $this->params;
        }
        if (!$action = $this->getAction()) {
            return [];
        }
        $this->params = [];
        $params = $action->getParameters();
        $routeParams = $this->request->route()->params();
        foreach ($params as $param) {
            if (array_has($routeParams, $param->name)) {
                $value = array_get($routeParams, $param->name);
            } elseif ($this->request->has($param->name)) {
                $value = $this->request->get($param->name);
            } else {
                $value = $param->getDefaultValue();
            }
            if ($param->hasType()) {
                $type = $param->getType();
                if (!$type->isBuiltin()) {
                    $abstract = string_wrap($type);
                    $alias = string_end($abstract, '\\');
                    $value = Factory::make($alias, $abstract, $value);
                }
            }
            $this->params[$param->name] = $value;
        }
        return $this->params;
    }
}

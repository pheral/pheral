<?php

namespace Pheral\Essential\Core;

use Pheral\Essential\Core\Interfaces\Executable;
use Pheral\Essential\Exceptions\NetworkException;
use Pheral\Essential\Network\Frame;
use Pheral\Essential\Network\Output\Response;
use Pheral\Essential\Network\Routing\Route;

class Network implements Executable
{
    /**
     * @var \Pheral\Essential\Network\Output\Response|null
     */
    protected $response;

    /**
     * @var \Pheral\Essential\Network\Frame
     */
    protected $frame;

    public function execute()
    {
        $this->frame = Frame::instance();
        if ($route = $this->frame->route()) {
            $controller = $this->getController($route);
            $action = $this->getAction($route, $controller);
            $params = $this->getParams($route, $action);
            $response = $action->invokeArgs($controller, $params);
        } else {
            throw new NetworkException(404, 'Page not found');
        }
        if (!$response instanceof Response) {
            $response = Response::make($response);
        }
        $this->response = $response->send();
    }

    protected function getController(Route $route)
    {
        $abstract = $route->controller();
        try {
            $reflection = new \ReflectionClass($abstract);
        } catch (\ReflectionException $exception) {
            throw new NetworkException(500, "class {$abstract} does not exists");
        }
        $constructor = $reflection->getConstructor();
        if ($constructor && $constructor->isPublic()) {
            $controller = $reflection->newInstance();
        } else {
            $controller = $reflection->newInstanceWithoutConstructor();
        }
        return $controller;
    }

    protected function getAction(Route $route, $controller)
    {
        try {
            $reflection = new \ReflectionMethod($controller, $route->action());
        } catch (\ReflectionException $exception) {
            throw new NetworkException(500, $exception->getMessage());
        }
        return $reflection;
    }

    protected function getParams(Route $route, \ReflectionMethod $action)
    {
        $params = [];
        if ($actionParams = $action->getParameters()) {
            $routeParams = $route->params();
            $request = $this->frame->request();
            foreach ($actionParams as $param) {
                if (array_has($routeParams, $param->name)) {
                    $value = array_get($routeParams, $param->name);
                } elseif ($request->has($param->name)) {
                    $value = $request->get($param->name);
                } elseif ($param->isDefaultValueAvailable()) {
                    $value = $param->getDefaultValue();
                }
                if ($param->hasType()) {
                    $type = $param->getType();
                    if (!$type->isBuiltin()) {
                        $value = null;
                    }
                }
                $params[$param->name] = $value ?? null;
            }
        }
        return $params;
    }

    public function terminate()
    {
        if (!$this->frame->isAjaxRequest() && $this->frame->isRequestMethod('GET')) {
            $this->frame->session()->setPreviousUrl($this->frame->getCurrentUrl());
        }
        if ($this->response && $this->response->hasRedirect()) {
            $this->frame->session()->setRedirectedUrl($this->response->redirect()->getUrl());
        }
    }
}

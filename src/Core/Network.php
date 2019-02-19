<?php

namespace Pheral\Essential\Core;

use Pheral\Essential\Core\Interfaces\Executable;
use Pheral\Essential\Exceptions\NetworkException;
use Pheral\Essential\Layers\Wrapper;
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

    protected $wrappers = [];

    public function execute()
    {
        $this->frame = Frame::instance();
        if ($route = $this->frame->route()) {
            $routeWrappers = $route->wrappers();
            $configWrappers = config('app.wrappers.network', []);
            $wrappers = array_merge($configWrappers, $routeWrappers);
            $response = $wrappers
                ? $this->handleWrappers($wrappers)
                : $this->handleRoute();
        } else {
            throw new NetworkException(404, 'Page not found');
        }
        if (!$response instanceof Response) {
            $response = Response::make($response);
        }
        $this->response = $response->send();
    }

    protected function handleRoute()
    {
        $route = $this->frame->route();
        $controller = $this->getController($route);
        $action = $this->getAction($route, $controller);
        $params = $this->getParams($route, $action);
        return $action->invokeArgs($controller, $params);
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

    protected function handleWrappers(array $wrappers)
    {
        $callNext = function () use (&$callNext, &$wrappers) {
            if ($nextWrapper = next($wrappers)) {
                return $this->handleWrapper($nextWrapper, $callNext);
            }
            return $this->handleRoute();
        };
        $wrapper = reset($wrappers);
        return $this->handleWrapper($wrapper, $callNext);
    }

    protected function handleWrapper($wrapperClass, callable $callNext)
    {
        $wrapper = new $wrapperClass($callNext);
        if (!$wrapper instanceof Wrapper) {
            throw new NetworkException(500, 'Settings error');
        }
        $this->wrappers[] = $wrapper;
        return $wrapper->handle();
    }

    protected function terminateWrapper(Wrapper $wrapper)
    {
        $wrapper->terminate($this->response);
    }

    public function terminate()
    {
        if ($this->wrappers) {
            $wrappers = array_reverse($this->wrappers);
            foreach ($wrappers as $wrapperClass) {
                $this->terminateWrapper($wrapperClass);
            }
        }
    }
}

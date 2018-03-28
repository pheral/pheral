<?php

namespace Pheral\Essential\Core;

use Pheral\Essential\Core\Interfaces\Executable;
use Pheral\Essential\Container\Pool;
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
    protected $frame;
    public function __construct()
    {
        Pool::singleton('Frame', Frame::class);
        $this->frame = Frame::instance();
    }
    public function execute()
    {
        $response = null;
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
        return Pool::make($route->controller());
    }
    protected function getAction(Route $route, $controller)
    {
        return new \ReflectionMethod($controller, $route->action());
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
                        $abstract = string_wrap($type);
                        $value = Pool::make($abstract, null, $params ?? []);
                    }
                }
                $params[$param->name] = $value ?? null;
            }
        }
        return $params;
    }
    public function terminate()
    {
        $frame = $this->frame;
        $session = $frame->session();
        if (!$frame->isAjaxRequest() && $frame->isRequestMethod('GET')) {
            $session->setPreviousUrl($frame->getCurrentUrl());
        }
        $response = $this->response;
        if ($response && $response->hasRedirect()) {
            $session->setRedirectedUrl($response->redirect()->getUrl());
        }
    }
}

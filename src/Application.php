<?php

namespace Pheral\Essential;

use Pheral\Essential\Container\Factory;
use Pheral\Essential\Network\Frame;
use Pheral\Essential\Network\Output\Response;

class Application
{
    protected $develop = true;

    protected function boot()
    {
        Factory::singleton('Frame', Frame::class);
    }

    public function run()
    {
        try {
            $this->boot();
            $response = $this->makeResponse();
            $response->send();
            $this->terminate($response);
        } catch (\Throwable $exception) {
            debug([
                'MESSAGE' => $exception->getMessage(),
                'PLACE' => $exception->getFile() . ':' . $exception->getLine(),
                'TRACE' => PHP_EOL . $exception->getTraceAsString()
            ]);
        }
    }

    protected function makeResponse(): Response
    {
        $data = null;
        $frame = Frame::instance();
        if ($route = $frame->route()) {
            $controller = Factory::make($route->controller());
            $action = new \ReflectionMethod($controller, $route->action());
            $params = [];
            if ($actionParams = $action->getParameters()) {
                $routeParams = $route->params();
                $request = $frame->request();
                foreach ($action->getParameters() as $param) {
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
                            $alias = string_end($abstract, '\\');
                            $value = Factory::make($alias, $abstract, $value ?? []);
                        }
                    }
                    $params[$param->name] = $value ?? null;
                }
            }
            $data = $action->invokeArgs($controller, $params);
        }
        return $data instanceof Response ? $data : new Response($data);
    }

    protected function terminate(Response $response)
    {
        $frame = Frame::instance();

        if ($frame->isDirect()) {
            $frame->session()->set('_url.previous', $frame->getCurrentUrl());
        }
        if ($response->hasRedirect()) {
            $frame->session()->set('_url.redirected', $response->redirect()->getUrl());
        }
        if ($this->develop && !$response->hasContent() && !$response->hasRedirect()) {
            debug('empty content', $frame);
        }
    }
}

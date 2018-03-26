<?php

namespace Pheral\Essential;

use Pheral\Essential\Data\Cookies;
use Pheral\Essential\Data\Server;
use Pheral\Essential\Data\Session;
use Pheral\Essential\Network\Core;
use Pheral\Essential\Network\Request;
use Pheral\Essential\Network\Response;
use Pheral\Essential\Network\Routing\Router;
use Pheral\Essential\Container\Factory;

class Application
{
    public function __construct()
    {
        //
    }
    protected function boot(): Request
    {
        return Factory::singleton('Request', Request::class, [
            Factory::singleton('Server', Server::class),
            Factory::singleton('Session', Session::class),
            Factory::singleton('Cookies', Cookies::class),
            Factory::singleton('Router', Router::class),
        ]);
    }
    public function run()
    {
        try {
            $request = $this->boot();
            $response = (new Core($request))->handle();
            if ($response->hasRedirect()) {
                $response->redirect()->send();
            } else {
                $response->send();
            }
            $this->terminate($request, $response);
        } catch (\Throwable $exception) {
            debug([
                'MESSAGE' => $exception->getMessage(),
                'PLACE' => $exception->getFile() . ':' . $exception->getLine(),
                'TRACE' => PHP_EOL . $exception->getTraceAsString()
            ]);
        }
    }
    protected function terminate(Request $request, Response $response)
    {
        if ($request->isDirect()) {
            $request->session()->set('_url.previous', $request->getCurrentUrl());
        }
        if ($response->hasRedirect()) {
            $request->session()->set('_url.redirected', $response->redirect()->getUrl());
        }
    }
}

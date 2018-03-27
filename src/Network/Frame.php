<?php

namespace Pheral\Essential\Network;

use Pheral\Essential\Data\Cookies;
use Pheral\Essential\Data\Server;
use Pheral\Essential\Data\Session;
use Pheral\Essential\Data\Request;
use Pheral\Essential\Network\Routing\Router;
use Pheral\Essential\Container\Pool;

class Frame
{
    protected $requestMethod;
    protected $currentUrl;
    protected $previousUrl;
    protected $protocol;
    protected $requestUri;
    protected $queryString;
    protected $route;
    protected $request;
    protected $cookies;
    protected $server;
    protected $session;
    public static function instance(): Frame
    {
        return Pool::get('Frame');
    }
    public function __construct()
    {
        $this->init();

        $this->server = Server::instance();
        $this->session = Session::instance();
        $this->cookies = Cookies::instance();
        $this->request = Request::instance();

        $this->protocol = $this->server->isSecure() ? 'https' : 'http';
        $this->currentUrl = $this->protocol . '://' . $this->server->getHost() . $this->server->getRequestUri();
        $this->session->setCurrentUrl($this->currentUrl)->refreshRedirected();
        if (!$this->previousUrl = $this->server->getReferer()) {
            $this->previousUrl = $this->session->getPreviousUrl();
        }
        $this->requestMethod = $this->server->getRequestMethod();
        if ($this->requestMethod === 'POST' && $this->server->isXmlHttpRequest()) {
            $this->requestMethod = strtoupper($this->request->get('_method', $this->requestMethod));
        }
        $this->route = Router::instance()
            ->load($this->server)
            ->find($this->currentUrl, $this->requestMethod);
    }
    protected function init()
    {
        Pool::singleton('Server', Server::class);
        Pool::singleton('Session', Session::class);
        Pool::singleton('Cookies', Cookies::class);
        Pool::singleton('Request', Request::class);
        Pool::singleton('Router', Router::class);
    }
    public function getProtocol()
    {
        return $this->protocol;
    }
    public function getHost()
    {
        return $this->server->getHost();
    }
    public function getPreviousUrl()
    {
        return $this->previousUrl;
    }
    public function getCurrentUrl()
    {
        return $this->currentUrl;
    }
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }
    public function isRequestMethod($method)
    {
        return $this->requestMethod === strtoupper($method);
    }
    public function isAjaxRequest()
    {
        return $this->server->isXmlHttpRequest();
    }
    /**
     * @return \Pheral\Essential\Network\Routing\Route|null
     */
    public function route()
    {
        return $this->route;
    }
    public function server(): Server
    {
        return $this->server;
    }
    public function session(): Session
    {
        return $this->session;
    }
    public function cookies(): Cookies
    {
        return $this->cookies;
    }
    public function request(): Request
    {
        return $this->request;
    }
}

<?php

namespace Pheral\Essential\Network;

use Pheral\Essential\Storage\Config;
use Pheral\Essential\Storage\Cookies;
use Pheral\Essential\Storage\Server;
use Pheral\Essential\Storage\Session;
use Pheral\Essential\Storage\Request;
use Pheral\Essential\Network\Routing\Router;

class Frame
{
    private static $instance;
    protected $requestMethod;
    protected $currentUrl;
    protected $previousUrl;
    protected $protocol;
    protected $requestUri;
    protected $queryString;
    protected $config;
    protected $server;
    protected $session;
    protected $cookies;
    protected $request;
    /**
     * @var \Pheral\Essential\Network\Routing\Route|null $route
     */
    protected $route;
    private function __construct()
    {
        $this->config = Config::instance();
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
        $this->route = Router::instance()->load()->find($this->currentUrl, $this->requestMethod);
    }
    private function __clone()
    {
    }
    public static function instance(): Frame
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
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
    public function route()
    {
        return $this->route;
    }
    public function config(): Config
    {
        return $this->config;
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

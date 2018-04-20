<?php

namespace Pheral\Essential\Network;

use Pheral\Essential\Data\Config;
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

    /**
     * @var \Pheral\Essential\Data\Server $server
     */
    protected $server;

    /**
     * @var \Pheral\Essential\Data\Session $session
     */
    protected $session;

    /**
     * @var \Pheral\Essential\Data\Cookies $cookies
     */
    protected $cookies;

    /**
     * @var \Pheral\Essential\Data\Request $request
     */
    protected $request;

    /**
     * @var \Pheral\Essential\Network\Routing\Route|null $route
     */
    protected $route;

    public static function instance(): Frame
    {
        return Pool::get('Frame');
    }

    public function __construct()
    {
        $this->server = Pool::singleton('Server', Server::class);
        $this->session = Pool::singleton('Session', Session::class);
        $this->cookies = Pool::singleton('Cookies', Cookies::class);
        $this->request = Pool::singleton('Request', Request::class);
        Pool::singleton('Router', Router::class);

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
        return Config::instance();
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

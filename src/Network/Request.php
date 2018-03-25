<?php

namespace Pheral\Essential\Network;

use Pheral\Essential\Data\Cookies;
use Pheral\Essential\Data\Files;
use Pheral\Essential\Data\Headers;
use Pheral\Essential\Data\Server;
use Pheral\Essential\Data\Session;
use Pheral\Essential\Network\Routing\Router;
use Pheral\Essential\Tools\Factory;

class Request
{
    protected $route;
    protected $method;
    protected $currentUrl;
    protected $previousUrl;
    protected $protocol;
    protected $host;
    protected $uriString;
    protected $queryString;
    protected $data = [];
    protected $files;
    protected $routes;
    protected $cookies;
    protected $server;
    protected $session;
    public function __construct(Server $server, Session $session, Cookies $cookies, Router $routes)
    {
        $this->data =& ${'_REQUEST'};
        $this->files = Factory::singleton('Files', Files::class);
        $this->server = $server;
        $this->session = $session;
        $this->cookies = $cookies;
        $this->routes = $routes;

        // инициализация основных данных
        $this->routes->load();
        $this->protocol = $server->isSecure() ? 'https' : 'http';
        $this->host = $server->get('HTTP_HOST');
        $this->queryString = $server->get('QUERY_STRING');
        $this->uriString = $server->get('REQUEST_URI');
        $this->currentUrl = $this->protocol . '://' . $this->host . $this->uriString;
        if (!$this->previousUrl = $server->get('HTTP_REFERER')) {
            $this->previousUrl = $this->session()->get('_url.previous', $this->currentUrl);
        }
        $this->method = strtoupper($server->get('REQUEST_METHOD', 'GET'));
        if ($this->method === 'POST') {
            if ($method = $server->get('HTTP_X_METHOD_OVERRIDE')) {
                $this->method = strtoupper($method);
            } elseif ($this->isAjax()) {
                $this->method = strtoupper($this->get('_method', 'POST'));
            }
        }
        $this->route = $this->routes->find($this->currentUrl, $this->method);
    }
    public function all(): array
    {
        return $this->data;
    }
    public function has($key)
    {
        return array_has($this->data, $key);
    }
    public function get($key, $default = null)
    {
        return array_get($this->data, $key, $default);
    }
    public function set($key, $value)
    {
        array_set($this->data, $key, $value);
        return $this;
    }
    public function cut($key, $default = null)
    {
        return array_cut($this->data, $key, $default);
    }
    public function clear()
    {
        $this->data = [];
        return $this;
    }
    public function getProtocol()
    {
        return $this->protocol;
    }
    public function getHost()
    {
        return $this->host;
    }
    public function getUriString()
    {
        return $this->uriString;
    }
    public function getPreviousUrl()
    {
        return $this->previousUrl;
    }
    public function getCurrentUrl()
    {
        return $this->currentUrl;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function isMethod($method)
    {
        return $this->method === strtoupper($method);
    }
    public function isAjax()
    {
        return $this->server->isXmlHttpRequest();
    }
    public function isDirect()
    {
        return !$this->isAjax() && $this->isMethod('GET');
    }
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
    public function files(): Files
    {
        return $this->files;
    }
    public function headers(): Headers
    {
        return $this->server->headers();
    }
}

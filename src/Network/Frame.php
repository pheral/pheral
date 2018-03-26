<?php

namespace Pheral\Essential\Network;

use Pheral\Essential\Data\Cookies;
use Pheral\Essential\Data\Files;
use Pheral\Essential\Data\Headers;
use Pheral\Essential\Data\Server;
use Pheral\Essential\Data\Session;
use Pheral\Essential\Data\Request;
use Pheral\Essential\Network\Output\Response;
use Pheral\Essential\Network\Routing\Router;
use Pheral\Essential\Container\Factory;
use Pheral\Essential\Container\Pool;

class Frame
{
    protected $requestMethod;
    protected $currentUrl;
    protected $previousUrl;
    protected $redirectedUrl;
    protected $protocol;
    protected $host;
    protected $requestUri;
    protected $queryString;
    protected $route;
    protected $request;
    protected $cookies;
    protected $server;
    protected $session;
    public function __construct()
    {
        $this->server = Factory::singleton('_Server', Server::class);
        $this->session = Factory::singleton('_Session', Session::class);
        $this->cookies = Factory::singleton('_Cookies', Cookies::class);
        $this->request = Factory::singleton('_Request', Request::class);

        $this->init(Factory::singleton('Router', Router::class));
    }
    public static function instance(): Frame
    {
        return Pool::get('Frame');
    }
    protected function init(Router $router)
    {
        $server = $this->server();
        $this->protocol = $server->isSecure() ? 'https' : 'http';
        $this->host = $server->get('HTTP_HOST');
        $this->queryString = $server->get('QUERY_STRING');
        $this->requestUri = $server->get('REQUEST_URI');
        $this->currentUrl = $this->protocol . '://' . $this->host . $this->requestUri;
        if (!$this->previousUrl = $server->get('HTTP_REFERER')) {
            $this->previousUrl = $this->session()->get('_url.previous', $this->currentUrl);
        }
        $this->redirectedUrl = $this->session()->get('_url.redirected');
        $this->requestMethod = strtoupper($server->get('REQUEST_METHOD', 'GET'));
        if ($this->requestMethod === 'POST') {
            if ($method = $server->get('HTTP_X_METHOD_OVERRIDE')) {
                $this->requestMethod = strtoupper($method);
            } elseif ($this->isAjax()) {
                $this->requestMethod = strtoupper($this->request()->get('_method', 'POST'));
            }
        }
        $this->route = $router->load()->find($this->currentUrl, $this->requestMethod);
    }
    public function handle(): Response
    {
        $data = null;
        if ($route = $this->route()) {
            $controller = Factory::make($route->controller());
            $action = new \ReflectionMethod($controller, $route->action());
            $params = [];
            if ($actionParams = $action->getParameters()) {
                $routeParams = $route->params();
                $request = $this->request();
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
    public function getProtocol()
    {
        return $this->protocol;
    }
    public function getHost()
    {
        return $this->host;
    }
    public function getRequestUri()
    {
        return $this->requestUri;
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
    public function isMethod($method)
    {
        return $this->requestMethod === strtoupper($method);
    }
    public function isAjax()
    {
        return $this->server()->isXmlHttpRequest();
    }
    public function isDirect()
    {
        return !$this->isAjax() && $this->isMethod('GET');
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
    public function request(): Request
    {
        return $this->request;
    }
    public function files(): Files
    {
        return $this->request()->files();
    }
    public function headers(): Headers
    {
        return $this->server()->headers();
    }
}

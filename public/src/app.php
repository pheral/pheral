<?php
namespace {
    require_once 'functions/helpers.php';
    require_once 'functions/tools.php';

    class Storage
    {
        private static $instances = [];
        private function __construct()
        {
            //
        }
        private function __clone()
        {
            //
        }
        public static function get($alias)
        {
            return array_get(self::$instances, $alias);
        }
        public static function set($alias, $instance)
        {
            array_set(self::$instances, $alias, $instance);
            return $instance;
        }
    }
    class Factory
    {
        public static function make($alias, $abstract = null, $params = [], $singleton = false)
        {
            if ($instance = Storage::get($alias)) {
                return $instance;
            }
            if (!$abstract) {
                $abstract = $alias;
                $alias = is_string($abstract) ? string_end($abstract, '\\') : get_class($abstract);
            }
            if (is_string($abstract) && class_exists($abstract)) {
                $reflection = new \ReflectionClass($abstract);
                $constructor = $reflection->getConstructor();
                if ($constructor && $constructor->isPublic()) {
                    $args = array_wrap($params, false);
                    $instance = $reflection->newInstanceArgs($args);
                } else {
                    $instance = $reflection->newInstanceWithoutConstructor();
                }
            } else {
                $instance = $abstract;
            }
            if (!$singleton) {
                return $instance;
            }
            return Storage::set($alias, $instance);
        }
        public static function singleton($alias, $abstract = null, $params = [])
        {
            return self::make($alias, $abstract, $params, true);
        }
    }

    class Server
    {
        protected $data = [];
        protected $headers;
        protected $isSecure;
        protected $isXmlHttpRequest;
        public function __construct()
        {
            $this->data = ${'_SERVER'};
            $this->headers = Factory::singleton('Headers', Headers::class, $this);
        }
        public function all(): array
        {
            return $this->data;
        }
        public function has($key): bool
        {
            return array_has($this->data, $key);
        }
        public function get($key = '', $default = null)
        {
            return array_get($this->data, $key, $default);
        }
        public function headers(): Headers
        {
            return $this->headers;
        }
        public function isSecure(): bool
        {
            if (is_null($this->isSecure)) {
                $isHttpsOn = $this->has('HTTPS') && $this->get('HTTPS') !== 'off';
                $isHttpsPort = $this->get('SERVER_PORT') === 443;
                $this->isSecure = $isHttpsOn || $isHttpsPort;
            }
            return $this->isSecure;
        }
        public function isXmlHttpRequest(): bool
        {
            if (is_null($this->isXmlHttpRequest)) {
                $xmlHttpRequestedWith = $this->get('HTTP_X_REQUESTED_WITH', '');
                $this->isXmlHttpRequest = strtoupper($xmlHttpRequestedWith) === 'XMLHTTPREQUEST';
            }
            return $this->isXmlHttpRequest;
        }
    }
    class Session
    {
        protected $data = [];
        public function __construct()
        {
            if (!session_id()) {
                session_start();
            }
            $this->data =& ${'_SESSION'};
        }
        public function all(): array
        {
            return $this->data;
        }
        public function has($key): bool
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
            session_unset();
            return $this;
        }
    }
    class Cookies
    {
        protected $data = [];
        public function __construct()
        {
            $this->data =& ${'_COOKIE'};
        }
        public function all(): array
        {
            return $this->data;
        }
        public function has($key): bool
        {
            return array_has($this->data, $key);
        }
        public function get($key, $default = null)
        {
            return array_get($this->data, $key, $default);
        }
        public function set($key, $value, $minutes = 0, $path = '', $domain = '', $secure = false, $httpOnly = true)
        {
            $time = $minutes ? time() + ($minutes * 60) : 0;
            setcookie($key, $value, $time, $path, $domain, $secure, $httpOnly);
            array_set($this->data, $key, $value);
            return $this;
        }
        public function drop($key)
        {
            $this->set($key, '', -60);
            $this->set($key, '', -60, '/');
            array_drop($this->data, $key);
            return $this;
        }
        public function cut($key, $default = null)
        {
            $value = $this->get($key, $default);
            $this->drop($key);
            return $value;
        }
        public function clear()
        {
            $keys = array_keys($this->data);
            foreach ($keys as $key) {
                if ($key === 'PHPSESSID') {
                    continue;
                }
                $this->drop($key);
            }
            return $this;
        }
    }
    class Headers
    {
        protected $data = [];
        public function __construct(\Server $server)
        {
            foreach ($server->all() as $key => $value) {
                if (strpos($key, 'HTTP_') !== 0) {
                    continue;
                }
                $headerName = implode('-', array_map(function ($segment) {
                    return ucfirst(strtolower($segment));
                }, explode('_', str_replace('HTTP_', '', $key))));
                $this->data[$headerName] = $value;
            }
        }
        public function all(): array
        {
            return $this->data;
        }
        public function get($key, $default = null)
        {
            return array_get($this->data, $key, $default);
        }
    }
    class Files
    {
        protected $data = [];
        public function __construct()
        {
            $this->data =& ${'_FILES'};
        }
        public function all(): array
        {
            return $this->data;
        }
        public function get($key, $default = null)
        {
            return array_get($this->data, $key, $default);
        }
    }

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
        public function __construct(Server $server, Session $session, Cookies $cookies, Routes $routes)
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

    class Response
    {
        protected $redirect;
        protected $content = '';
        public function __construct($data = null)
        {
            if ($data instanceof Redirect) {
                $this->setRedirect($data);
            } elseif (isset($data)) {
                $this->setContent($data);
            }
        }
        public function setContent($data)
        {
            if (!isset($data)) {
                return $this;
            }
            if ($data instanceof View) {
                $content = $data->render();
            } elseif (!is_string($data)) {
                $content = json_encode($data);
            } else {
                $content = $data;
            }
            $this->content = $content;
            return $this;
        }
        public function hasContent()
        {
            return strlen($this->content) > 0;
        }
        public function send()
        {
            echo $this->content;
        }
        public function setRedirect($target = '', $status = 302)
        {
            if ($target instanceof Redirect) {
                $this->redirect = $target;
            } else {
                $this->redirect = new Redirect($target, $status);
            }
            return $this;
        }

        public function hasRedirect(): bool
        {
            return $this->redirect instanceof Redirect;
        }
        /**
         * @return \Redirect|null
         */
        public function redirect()
        {
            return $this->redirect;
        }
    }
    class Redirect
    {
        protected $scheme;
        protected $host;
        protected $url;
        protected $status;
        protected $options;
        public function __construct($url = '', $status = 302)
        {
            if ($url) {
                $this->setUrl($url);
            }
            if ($status) {
                $this->setStatus($status);
            }
        }
        protected function getRequest(): Request
        {
            return Storage::get('Request');
        }
        public function setUrl($url)
        {
            $this->url = $url;
            return $this;
        }
        public function getUrl()
        {
            return $this->url;
        }
        public function setStatus($status)
        {
            $this->status = $status;
            return $this;
        }
        public function getStatus()
        {
            return $this->status;
        }
        public function back()
        {
            $target = $this->getRequest()->getPreviousUrl();
            return $this->setUrl($target);
        }
        public function send()
        {
            if (!$url = $this->getUrl()) {
                return ;
            }
            $location = $url;
            if (!parse_url($url, PHP_URL_HOST)) {
                $host = $this->getRequest()->getHost();
                $location = $host . '/' . ltrim($location, '/');
            }
            if (!parse_url($url, PHP_URL_SCHEME)) {
                $protocol = $this->getRequest()->getProtocol();
                $location = $protocol . '://' . ltrim($location, '://');
            }
            $status = $this->getStatus();
            header("Location: {$location}", true, $status);
        }
    }
    class Routes
    {
        protected $currentMethod;
        protected $data = [];
        public function load()
        {
            require_once 'routes.php';
        }
        public function add($pattern, $options = [])
        {
            array_set($this->data, $pattern, $options);
            return $this;
        }
        protected function setMethod($method)
        {
            $this->currentMethod = strtoupper($method);
            return $this;
        }
        public function find($url, $method = null)
        {
            if (is_null($this->currentMethod)) {
                $this->currentMethod = $method;
            }
            $method = $method ? strtoupper($method) : $this->currentMethod;
            $path = parse_url($url, PHP_URL_PATH);
            foreach ($this->data as $pattern => $options) {
                $methodOption = strtoupper(array_get($options, 'method'));
                if ($methodOption && $methodOption !== $method) {
                    continue;
                }
                if ($path === $pattern) {
                    $route = $options;
                    break;
                }
                $params = $this->parse($path, $pattern);
                if (is_null($params)) {
                    continue;
                }
                array_set($options, 'params', $params);
                $route = $options;
                break;
            }
            if (isset($route)) {
                $controller = array_get($route, 'controller');
                $action = array_get($route, 'action', 'index');
                $method = array_get($route, 'method', 'GET');
                $params = array_get($route, 'params', []);
                return new Route($controller, $action, $params, $method);
            }
            return null;
        }
        protected function parse($path, $pattern)
        {
            if (strpos($pattern, '{') === false) {
                return null;
            }
            $path = trim($path, '/ ');
            $pathSegments = explode('/', $path);

            $pattern = trim($pattern, '/ ');
            $patternSegments = explode('/', $pattern);
            $optionals = $this->findPatternResult('/\{([a-z0-9-_]+?)\?\}/si', $pattern);
            $requires = $this->findPatternResult('/\{([a-z0-9-_]+?)\}/si', $pattern);

            $success = true;
            $params = [];
            foreach ($patternSegments as $index => $patternSegment) {
                $pathSegment = array_get($pathSegments, $index);
                if ($pathSegment != $patternSegment) {
                    if ($required = array_get($requires, $patternSegment)) {
                        if (!$pathSegment) {
                            $success = false;
                            break;
                        }
                        $params[$required] = $pathSegment;
                        continue;
                    }
                    if ($optional = array_get($optionals, $patternSegment)) {
                        $params[$optional] = $pathSegment;
                        continue;
                    }
                    $success = false;
                    break;
                }
            }
            return $success ? $params : null;
        }
        protected function findPatternResult($regex, $subject)
        {
            $result = [];
            preg_match_all($regex, $subject, $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                list($mask, $value) = $match;
                $result[$mask] = $value;
            }
            return $result;
        }
        /**
         * @param $pattern
         * @return \Route|null
         */
        public function get($pattern)
        {
            return array_get($this->data, $pattern);
        }
        public function all(): array
        {
            return $this->data;
        }
    }
    class Router
    {
        protected $request;
        protected $controller;
        protected $action;
        protected $params;
        protected $redirect;
        protected $response;
        protected $isHandled = false;
        public function __construct(Request $request)
        {
            $this->request = $request;
        }
        public function request(): Request
        {
            return $this->request;
        }
        public function handle()
        {
            if ($this->isHandled) {
                return $this->response;
            }
            $controller = $this->getController();
            $action = $this->getAction();
            $data = null;
            if ($controller && $action) {
                $data = $action->invokeArgs($controller, $this->getParams());
            }
            if ($data instanceof Response) {
                $this->response = $data;
            } else {
                $this->response = new Response($data);
            }
            $this->isHandled = true;
            return $this->response;
        }
        protected function getController()
        {
            if (isset($this->controller)) {
                return $this->controller;
            }
            if (!$route = $this->request()->route()) {
                return null;
            }
            if (!$controller = $route->controller()) {
                return null;
            }
            if (is_null($this->controller)) {
                $controllerClass = Factory::make("\\" . $controller);
                $this->controller = $controllerClass ?? false;
            }
            return $this->controller;
        }
        protected function getAction()
        {
            if (isset($this->action)) {
                return $this->action;
            }
            if (!$route = $this->request()->route()) {
                return null;
            }
            if (!$action = $route->action()) {
                return null;
            }
            if (!$controller = $this->getController()) {
                return null;
            }
            if (is_null($this->action)) {
                $reflection = new \ReflectionMethod($controller, $action);
                $this->action = $reflection->isPublic() ? $reflection : false;
            }
            return $this->action;
        }
        protected function getParams()
        {
            if (isset($this->params)) {
                return $this->params;
            }
            if (!$action = $this->getAction()) {
                return [];
            }
            $this->params = [];
            $params = $action->getParameters();
            $routeParams = $this->request->route()->params();
            foreach ($params as $param) {
                if (array_has($routeParams, $param->name)) {
                    $value = array_get($routeParams, $param->name);
                } else {
                    $value = $this->request->get($param->name);
                }
                if ($param->hasType()) {
                    $type = $param->getType();
                    if (!$type->isBuiltin()) {
                        $abstract = string_wrap($type);
                        $alias = string_end($abstract, '\\');
                        $value = Factory::make($alias, $abstract, $value);
                    }
                }
                $this->params[$param->name] = $value;
            }
            return $this->params;
        }
        /**
         * @return \Response|null
         */
        public function response()
        {
            return $this->response;
        }
        public function hasResponse(): bool
        {
            return $this->isHandled && !empty($this->response);
        }
    }
    class Route
    {
        protected $controller;
        protected $action;
        protected $params = [];
        protected $method;
        public function __construct($controller = '', $action = '', $params = [], $method = '')
        {
            $this->controller = $controller;
            $this->action = $action;
            $this->params = $params;
            $this->method = $method;
        }
        public function controller()
        {
            return $this->controller;
        }
        public function action()
        {
            return $this->action;
        }
        public function method()
        {
            return $this->method;
        }
        public function params()
        {
            return $this->params;
        }
        public static function add($name, $options)
        {
            /**
             * @var \Routes $routes
             */
            $routes = Storage::get('Routes');
            $routes->add($name, $options);
        }
    }

    class View
    {
        protected $path;
        protected $data;
        public function __construct($path, $data = [])
        {
            $pathSegments = explode('.', trim($path, '.'));
            $file =  __DIR__ . '/views/' . implode('/', $pathSegments) . '.php';
            if (!file_exists($file)) {
                debug('Template "' . $path . '" not found ');
            }
            $this->path = __DIR__ . '/views/' . implode('/', $pathSegments) . '.php';
            $this->data = $data;
        }
        public function render($data = [])
        {
            $filePath = $this->getPath();
            if ($data = array_merge($this->getData(), $data)) {
                extract($data);
            }
            ob_start();
            include $filePath;
            return ob_get_clean();
        }
        public function getData()
        {
            return array_wrap($this->data, false);
        }
        public function getPath()
        {
            return $this->path;
        }
    }

    class Home
    {
        public function index(Request $request, $page = null)
        {
            $view = new View('home.index', [
                'page' => $page,
            ]);
            if ($request->files()->all()) {
                debug([
                    'page' => $page,
                    'server' => server()->all(),
                    'session' => session()->all(),
                    'cookies' => cookies()->all(),
                    'request' => [
                        'inputs' => $request->all(),
                        'files' => $request->files()->all(),
                        'headers' => $request->headers()->all(),
                    ],
                ]);
            }
            return $view;
        }
        public function about(\Request $request, $first, $middle = null, $last = null, $extra = null)
        {
            if ($request->has('redirect')) {
                return redirect()->back();
            }
            return new View('home.about', [
                'content' => 'test',
                'first' => $first,
                'middle' => $middle,
                'last' => $last,
                'extra' => $extra,
            ]);
        }
    }

    class Application
    {
        /**
         * @var \Request $request
         */
        protected $request;

        public function __construct()
        {
            //
        }
        protected function boot()
        {
            $this->request = Factory::singleton('Request', Request::class, [
                Factory::singleton('Server', Server::class),
                Factory::singleton('Session', Session::class),
                Factory::singleton('Cookies', Cookies::class),
                Factory::singleton('Routes', Routes::class),
            ]);
        }
        public function run()
        {
            try {
                $this->boot();
                $response = (new Router($this->request))->handle();
                if ($response->hasRedirect()) {
                    $response->redirect()->send();
                } else {
                    $response->send();
                }
                $this->terminate($this->request, $response);
            } catch (\Throwable $exception) {
                debug([
                    'MESSAGE' => $exception->getMessage(),
                    'PLACE' => $exception->getFile() . ':' . $exception->getLine(),
                    'TRACE' => PHP_EOL . $exception->getTraceAsString()
                ]);
            }
        }
        protected function terminate(\Request $request, \Response $response)
        {
            if ($request->isDirect()) {
                $request->session()->set('_url.previous', $request->getCurrentUrl());
            }
            if (!$response->hasContent() && !$response->hasRedirect()) {
                debug($response, $request);
            }
        }
    }

    (new Application())->run();
}

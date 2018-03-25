<?php

namespace Pheral\Essential\Network\Routing;

use Pheral\Essential\Data\Pool;

class Router
{
    protected $currentMethod;
    protected $data = [];
    public static function instance(): Router
    {
        return Pool::get('Router');
    }

    public function load()
    {
        $routesConfig = server()->path('app/routes.php');
        if (file_exists($routesConfig)) {
            require_once $routesConfig;
        }
    }
    public function add($pattern, $options = [])
    {
        array_set($this->data, $pattern, $options);
        return $this;
    }
    /**
     * @param $pattern
     * @return \Pheral\Essential\Network\Routing\Route|null
     */
    public function get($pattern)
    {
        return array_get($this->data, $pattern);
    }
    public function all(): array
    {
        return $this->data;
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
        $optionals = $this->parseMatches('/\{([a-z0-9-_]+?)\?\}/si', $pattern);
        $requires = $this->parseMatches('/\{([a-z0-9-_]+?)\}/si', $pattern);

        $success = true;
        $params = [];
        foreach ($patternSegments as $index => $patternSegment) {
            $pathSegment = array_get($pathSegments, $index);
            if ($pathSegment != $patternSegment) {
                if (strpos($patternSegment, '{') === false) {
                    $success = false;
                    break;
                }
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
    protected function parseMatches($regex, $subject)
    {
        $result = [];
        preg_match_all($regex, $subject, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            list($mask, $value) = $match;
            $result[$mask] = $value;
        }
        return $result;
    }
}

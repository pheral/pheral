<?php

namespace Pheral\Essential\Network\Routing;

use Pheral\Essential\Container\Pool;
use Pheral\Essential\Data\Server;

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
        $config = Server::instance()->path('app/routes.php');
        if (file_exists($config)) {
            require_once $config;
        }
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
            array_set($options, 'method', $method);
            $route = $options;
            break;
        }
        if (isset($route)) {
            return Route::make($route);
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
                    if ($pathSegment) {
                        $params[$required] = $pathSegment;
                        continue;
                    }
                    $success = false;
                    break;
                }
                if ($optional = array_get($optionals, $patternSegment)) {
                    if ($pathSegment) {
                        $params[$optional] = $pathSegment;
                    }
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

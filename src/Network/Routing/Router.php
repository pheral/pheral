<?php

namespace Pheral\Essential\Network\Routing;

use Pheral\Essential\Layers\Wrapper;

class Router
{
    private static $instance;
    protected $currentMethod;
    protected $data = [];
    protected $sources;
    private function __construct()
    {
    }
    private function __clone()
    {
    }
    protected function sources()
    {
        if (is_null($this->sources)) {
            $sources = [];
            if ($relativePath = config('app.routes', 'app/routes')) {
                if ($absolutePath = app()->path($relativePath)) {
                    $dir = dir($absolutePath);
                    while (false !== ($source = $dir->read())) {
                        if (is_dir($source)) {
                            continue;
                        }
                        $sources[] = $absolutePath . '/'. $source;
                    }
                }
            }
            $this->sources = $sources;
        }
        return $this->sources;
    }
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function load()
    {
        foreach ($this->sources() as $source) {
            require $source;
        }
        return $this;
    }
    public function wrap($wrappers, callable $closure)
    {
        $wrappers = array_wrap($wrappers);
        $notWrapped = $this->data;
        $closure();
        $wrapped = array_diff_key($this->data, $notWrapped);
        foreach ($wrapped as $pattern => $options) {
            $routeWrappers = array_get($options, 'wrappers', []);
            $options['wrappers'] = array_unique(array_merge($wrappers, $routeWrappers));
            $this->data[$pattern] = $options;
        }
    }
    public function add($pattern, $options = [])
    {
        $routeMethod = strtoupper(array_get($options, 'method', 'any'));
        array_set($this->data, $routeMethod.'@'.$pattern, $options);
        return $this;
    }
    public function find($url, $method = '')
    {
        if (is_null($this->currentMethod)) {
            $this->currentMethod = strtoupper($method);
        }
        $method = $method ? strtoupper($method) : $this->currentMethod;
        $path = parse_url($url, PHP_URL_PATH);
        foreach ($this->data as $key => $options) {
            list($routeMethod, $routePattern) = explode('@', $key);
            if ($routeMethod != 'ANY' && $routeMethod !== $method) {
                continue;
            }
            if ($path === $routePattern) {
                $route = $options;
                break;
            }
            $params = $this->parse($path, $routePattern);
            if (is_null($params)) {
                continue;
            }
            array_set($options, 'params', $params);
            array_set($options, 'method', $method);
            $route = $options;
            break;
        }
        if (isset($route)) {
            $this->filterWrappers($route);
            return Route::make($route);
        }
        return null;
    }

    protected function filterWrappers(array $options)
    {
        if ($routeWrappers = array_get($options, 'wrappers', [])) {
            $options['wrappers'] = array_filter($routeWrappers, function ($wrapper) {
                return is_subclass_of($wrapper, Wrapper::class);
            });
        }
        return $options;
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

        if (count($pathSegments) > count($patternSegments)) {
            return null;
        }

        $optionals = $this->parseMatches('/\{([a-z0-9-_]+?)\?\}/si', $pattern);
        $requires = $this->parseMatches('/\{([a-z0-9-_]+?)\}/si', $pattern);

        $success = true;
        $params = [];
        foreach ($patternSegments as $key => $patternSegment) {
            $pathSegment = array_get($pathSegments, $key);
            if ($pathSegment && !$patternSegment) {
                $success = false;
                break;
            }
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

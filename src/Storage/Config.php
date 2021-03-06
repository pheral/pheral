<?php

namespace Pheral\Essential\Storage;

class Config
{
    private static $instance;
    protected $path;
    protected $data = [];
    private function __construct()
    {
    }
    private function __clone()
    {
    }
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    protected function path()
    {
        if (is_null($this->path)) {
            $this->path = app()->path('config');
        }
        return $this->path;
    }
    public function load($dotPath = '')
    {
        if (!$segments = explode('.', $dotPath)) {
            return $this;
        }
        $path = $this->path();
        $pathSegments = [];
        while ($segment = array_shift($segments)) {
            $path .= '/' . $segment;
            if (file_exists($path) && is_dir($path)) {
                $pathSegments[] = $segment;
                continue;
            } elseif (file_exists($path . '.php')) {
                $pathSegments[] = $segment;
                $path .= '.php';
                break;
            }
            return $this;
        }
        $this->set(implode('.', $pathSegments), include $path);
        return $this;
    }
    public function has($key)
    {
        return dot_array_has($this->data, $key);
    }
    public function set($key, $value)
    {
        dot_array_set($this->data, $key, $value);
        return $this;
    }
    public function get($key = '', $default = null)
    {
        if (!$key) {
            return $this->data;
        }
        if (!array_has($this->data, string_start($key))) {
            $this->load($key);
        }
        return dot_array_get($this->data, $key, $default);
    }
}

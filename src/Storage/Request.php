<?php

namespace Pheral\Essential\Storage;

class Request
{
    private static $instance;
    protected $data = [];
    protected $files;
    private function __construct()
    {
        $this->data =& ${'_REQUEST'};
        $this->files = Files::instance();
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
    public function expel($key)
    {
        return array_expel($this->data, $key);
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
    public function files(): Files
    {
        return $this->files;
    }
}

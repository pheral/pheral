<?php

namespace Pheral\Essential\Data;

use Pheral\Essential\Container\Pool;

class Request
{
    protected $data = [];
    protected $files;
    public function __construct()
    {
        $this->data =& ${'_REQUEST'};

        Pool::singleton('Files', Files::class);
        $this->files = Files::instance();
    }
    public static function instance(): Request
    {
        return Pool::get('Request');
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

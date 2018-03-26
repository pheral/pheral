<?php

namespace Pheral\Essential\Data;

use Pheral\Essential\Container\Pool;

class Cookies
{
    protected $data = [];
    public function __construct()
    {
        $this->data =& ${'_COOKIE'};
    }
    public static function instance(): Cookies
    {
        return Pool::get('_Cookies');
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

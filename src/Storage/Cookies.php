<?php

namespace Pheral\Essential\Storage;

class Cookies
{
    private static $instance;
    protected $data = [];
    private function __construct()
    {
        $this->data =& ${'_COOKIE'};
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
    public function expel($key)
    {
        $this->set($key, '', -60);
        $this->set($key, '', -60, '/');
        array_expel($this->data, $key);
        return $this;
    }
    public function cut($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->expel($key);
        return $value;
    }
    public function clear()
    {
        $keys = array_keys($this->data);
        foreach ($keys as $key) {
            if ($key === 'PHPSESSID') {
                continue;
            }
            $this->expel($key);
        }
        return $this;
    }
}

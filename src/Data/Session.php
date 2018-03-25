<?php

namespace Pheral\Essential\Data;

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

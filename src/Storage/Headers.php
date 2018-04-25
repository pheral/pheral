<?php

namespace Pheral\Essential\Storage;

class Headers
{
    private static $instance;
    protected $data = [];
    private function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (strpos($key, 'HTTP_') !== 0) {
                continue;
            }
            $headerName = implode('-', array_map(function ($segment) {
                return ucfirst(strtolower($segment));
            }, explode('_', str_replace('HTTP_', '', $key))));
            $this->data[$headerName] = $value;
        }
    }
    private function __clone()
    {
    }
    public static function instance($data = [])
    {
        if (!self::$instance) {
            self::$instance = new self($data);
        }
        return self::$instance;
    }
    public function all(): array
    {
        return $this->data;
    }
    public function get($key, $default = null)
    {
        return array_get($this->data, $key, $default);
    }
    public function getHost()
    {
        return $this->get('Host');
    }
    public function getReferer()
    {
        return $this->get('Referer');
    }
}

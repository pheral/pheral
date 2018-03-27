<?php

namespace Pheral\Essential\Data;

use Pheral\Essential\Container\Pool;

class Headers
{
    protected $data = [];
    public function __construct($data = [])
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
    public static function instance(): Headers
    {
        return Pool::get('Headers');
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

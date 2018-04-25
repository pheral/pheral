<?php

namespace Pheral\Essential\Storage;

class Files
{
    private static $instance;
    protected $data = [];
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    private function __construct()
    {
        $this->data =& ${'_FILES'};
    }
    private function __clone()
    {
    }
    public function all(): array
    {
        return $this->data;
    }
    public function get($key, $default = null)
    {
        return array_get($this->data, $key, $default);
    }
}

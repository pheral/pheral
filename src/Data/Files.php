<?php

namespace Pheral\Essential\Data;

use Pheral\Essential\Container\Pool;

class Files
{
    protected $data = [];
    public function __construct()
    {
        $this->data =& ${'_FILES'};
    }
    public static function instance(): Files
    {
        return Pool::get('Files');
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

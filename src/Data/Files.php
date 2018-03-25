<?php

namespace Pheral\Essential\Data;

class Files
{
    protected $data = [];
    public function __construct()
    {
        $this->data =& ${'_FILES'};
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

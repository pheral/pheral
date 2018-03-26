<?php

namespace Pheral\Essential\Container;

class Pool
{
    private static $instances = [];
    private function __construct()
    {
        //
    }
    private function __clone()
    {
        //
    }
    public static function get($alias)
    {
        return array_get(self::$instances, $alias);
    }
    public static function set($alias, $instance)
    {
        array_set(self::$instances, $alias, $instance);
        return $instance;
    }
}

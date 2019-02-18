<?php

namespace Pheral\Essential\Storage\Database;

class DB
{
    private static $name;
    private static $connections = [];
    public static function connection(string $name = null): Connection
    {
        if (!$name && !self::$name) {
            self::$name = config('database.default');
        }
        $name = $name ? $name : self::$name;
        if (!$connection = array_get(self::$connections, $name)) {
            $connection = new Connection($name);
            self::$connections[$name] = $connection;
        }
        return $connection;
    }
    public static function setConnection(string $name)
    {
        self::$name = $name;
    }
    public static function query($table = null, $alias = '')
    {
        return self::connection()->query($table, $alias);
    }
    public static function execute($sql, $params = [])
    {
        return self::query()->execute($sql, $params);
    }
}

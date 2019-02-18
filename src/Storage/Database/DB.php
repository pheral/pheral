<?php

namespace Pheral\Essential\Storage\Database;

class DB
{
    private static $connectionName;
    private static $connections = [];
    public static function connection(string $connectionName = null): Connection
    {
        $connectionName = self::getConnectionName($connectionName);
        if (!$connection = array_get(self::$connections, $connectionName)) {
            $connection = new Connection($connectionName);
            self::$connections[$connectionName] = $connection;
        }
        return $connection;
    }
    public static function getConnectionName(string $connectionName = null)
    {
        if (!$connectionName && !self::$connectionName) {
            self::$connectionName = config('database.default');
        }
        return $connectionName ? $connectionName : self::$connectionName;
    }
    public static function setConnectionName(string $connectionName)
    {
        self::$connectionName = $connectionName;
    }
    public static function query($table = null, $alias = '', $connectionName = '')
    {
        return self::connection($connectionName)->query($table, $alias);
    }
    public static function execute($sql, $params = [], $connectionName = '')
    {
        return self::connection($connectionName)->query()->execute($sql, $params);
    }
}

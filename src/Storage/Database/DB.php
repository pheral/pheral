<?php

namespace Pheral\Essential\Storage\Database;

class DB
{
    private static $connectName;
    private static $connects = [];
    public static function connect(string $connectName = null): Connect
    {
        $connectName = self::getConnectName($connectName);
        if (!$connect = array_get(self::$connects, $connectName)) {
            $connect = new Connect($connectName);
            self::$connects[$connectName] = $connect;
        }
        return $connect;
    }
    public static function getConnectName(string $connectName = null)
    {
        if (!$connectName && !self::$connectName) {
            self::$connectName = config('database.default');
        }
        return $connectName ? $connectName : self::$connectName;
    }
    public static function setConnectName(string $connectName)
    {
        self::$connectName = $connectName;
    }
    public static function query($table = null, $alias = '', $connectName = '')
    {
        return self::connect($connectName)->query($table, $alias);
    }
    public static function execute($sql, $params = [], $connectName = '')
    {
        return self::connect($connectName)->query()->execute($sql, $params);
    }
}

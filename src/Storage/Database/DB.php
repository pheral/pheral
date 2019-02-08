<?php

namespace Pheral\Essential\Storage\Database;

use Pheral\Essential\Exceptions\NetworkException;
use Pheral\Essential\Layers\DataTable;
use Pheral\Essential\Storage\Database\Result\QueryResult;

class DB
{
    private static $instance;
    private static $history = [];
    private static $tableNames = [];
    private static $prefix = '';
    public static function instance(): \PDO
    {
        if (!self::$instance) {
            $cfg = config('database.connections.default');
            try {
                $driver = array_get($cfg, 'driver', 'mysql');
                $host = array_get($cfg, 'host', 'localhost');
                $user = array_get($cfg, 'user', 'pheral');
                $pass = array_get($cfg, 'pass', 'pheral');
                $base = array_get($cfg, 'base', 'pheral');
                $charset = array_get($cfg, 'charset', 'utf8');
                $dsn = "{$driver}:dbname={$base};host={$host};charset={$charset}";
                $opt = [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
                ];
                self::$instance = new \PDO($dsn, $user, $pass, $opt);
            } catch (\PDOException $e) {
                throw new NetworkException(500, $e->getMessage());
            }
        }
        return self::$instance;
    }
    public static function execute($sql, $params = []): \PDOStatement
    {
        self::$history[] = self::makeSql($sql, $params);
        $stmt = self::instance()->prepare(trim($sql));
        if ($params) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }
        return $stmt;
    }
    public static function query($sql, $params = [])
    {
        $stmt = self::execute($sql, $params);
        return new QueryResult($stmt);
    }
    public static function history()
    {
        return self::$history;
    }
    public static function setPrefix(string $prefix)
    {
        self::$prefix = $prefix;
    }
    public static function prefix()
    {
        return self::$prefix ? self::$prefix . '_' : '';
    }
    public static function tableName($table)
    {
        if (strpos($table, '\\', true) === false) {
            return $table;
        }
        if ($tableName = array_get(self::$tableNames, $table)) {
            return $tableName;
        }
        if (is_subclass_of($table, DataTable::class)) {
            $tableName = self::prefix() . string_snake_case(class_name($table));
            self::$tableNames[$table] = $tableName;
            return $tableName;
        }
        return '';
    }
    protected static function makeSql($sql, $params = [])
    {
        if ($params) {
            $search = array_keys($params);
            $replace = array_values($params);
            array_walk($replace, function (&$param) {
                $param = '"' . $param . '"';
            });
            $sql = str_replace($search, $replace, $sql);
        }
        return trim($sql);
    }
}

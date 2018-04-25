<?php

namespace Pheral\Essential\Storage\Database;

use Pheral\Essential\Exceptions\NetworkException;

class DB
{
    private static $instance;
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
                $dsn = "{$driver}:dbname={$base};host={$host};charset{$charset}";
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
        $stmt = self::instance()->prepare(trim($sql));
        if ($params) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }
        return $stmt;
    }
    public static function query($table = '', $alias = '')
    {
        return new Query($table, $alias);
    }
}

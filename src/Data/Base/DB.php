<?php

namespace Pheral\Essential\Data\Base;

use \PDO;
use \PDOException;
use Pheral\Essential\Exceptions\NetworkException;

class DB
{
    protected static $connect;

    /**
     * @return PDO
     */
    public static function connect()
    {
        if (!static::$connect) {
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
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                ];
                static::$connect = new PDO($dsn, $user, $pass, $opt);
            } catch (PDOException $e) {
                throw new NetworkException(500, $e->getMessage());
            }
        }
        return static::$connect;
    }

    public static function execute($sql, $params = [])
    {
        $stmt = static::connect()->prepare(trim($sql));
        if ($params) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }
        return $stmt;
    }
}

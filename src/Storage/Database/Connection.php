<?php

namespace Pheral\Essential\Storage\Database;

use Pheral\Essential\Exceptions\NetworkException;
use Pheral\Essential\Storage\Profiler;

class Connection
{
    public $name;
    public $prefix;
    protected $tables = [];
    protected $pdo;
    public function __construct(string $name)
    {
        $this->name = $name;
        $config = config('database.connections.' . $this->name);
        try {
            $driver = array_get($config, 'driver', 'mysql');
            $host = array_get($config, 'host', 'localhost');
            $user = array_get($config, 'user', 'root');
            $pass = array_get($config, 'pass', '');
            $base = array_get($config, 'base', '');
            $charset = array_get($config, 'charset', 'utf8');
            $dsn = "{$driver}:dbname={$base};host={$host};charset={$charset}";
            $opt = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            ];
            $this->pdo = new \PDO($dsn, $user, $pass, $opt);
            $this->prefix = array_get($config, 'prefix', '');
        } catch (\PDOException $e) {
            throw new NetworkException(500, $e->getMessage());
        }
    }

    public function query(string $table = null, string $alias = null)
    {
        return new Query($this, $table, $alias);
    }

    public function execute($sql, $params = []): \PDOStatement
    {
        Profiler::instance()->database()->push(
            $this->getSql($sql, $params),
            $this->name
        );
        $stmt = $this->getPdo()->prepare(trim($sql));
        if ($params) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }
        return $stmt;
    }

    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

    public function getSql($sql, $params = [])
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

    public function getTableClass($table)
    {
        if (is_subclass_of($table, DBTable::class)) {
            return $table;
        }
        return null;
    }

    public function getTableName($table)
    {
        if (strpos($table, '\\', true) === false) {
            return $table;
        }
        if ($tableName = array_get($this->tables, $table)) {
            return $tableName;
        }
        if (is_subclass_of($table, DBTable::class)) {
            $prefix = $this->prefix ? $this->prefix . '_' : '';
            $tableName = $prefix . string_snake_case(class_name($table));
            $this->tables[$table] = $tableName;
            return $tableName;
        }
        return '';
    }
}

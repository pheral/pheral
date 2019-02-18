<?php

namespace Pheral\Essential\Storage\Database;

use Pheral\Essential\Exceptions\NetworkException;
use Pheral\Essential\Layers\DataTable;
use Pheral\Essential\Storage\Profiler;

class Connect
{
    public $connectName;
    public $tablePrefix;
    protected $tableNames = [];
    protected $pdo;
    public function __construct(string $connectName)
    {
        $this->setPdo($connectName);
    }
    protected function setPdo(string $connectName)
    {
        $config = config('database.connections.' . $connectName);
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
            $this->connectName = $connectName;
            $this->tablePrefix = array_get($config, 'table_prefix', '');
        } catch (\PDOException $e) {
            throw new NetworkException(500, $e->getMessage());
        }
        return $this;
    }
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
    public function query($table = null, $alias = '')
    {
        return new Query($this, $table, $alias);
    }
    public function execute($sql, $params = []): \PDOStatement
    {
        Profiler::instance()->database()->push(
            $this->getSql($sql, $params),
            $this->connectName
        );
        $stmt = $this->getPdo()->prepare(trim($sql));
        if ($params) {
            $stmt->execute($params);
        } else {
            $stmt->execute();
        }
        return $stmt;
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
        if (is_subclass_of($table, DataTable::class)) {
            return $table;
        }
        return null;
    }
    public function getTableName($table)
    {
        if (strpos($table, '\\', true) === false) {
            return $table;
        }
        if ($tableName = array_get($this->tableNames, $table)) {
            return $tableName;
        }
        if (is_subclass_of($table, DataTable::class)) {
            $prefix = $this->tablePrefix ? $this->tablePrefix . '_' : '';
            $tableName = $prefix . string_snake_case(class_name($table));
            $this->tableNames[$table] = $tableName;
            return $tableName;
        }
        return '';
    }
}

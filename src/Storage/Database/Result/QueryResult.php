<?php

namespace Pheral\Essential\Storage\Database\Result;

class QueryResult
{
    protected $stmt;
    public function __construct(\PDOStatement $stmt)
    {
        $this->stmt = $stmt;
    }
    public function count()
    {
        return $this->stmt->rowCount();
    }
    public function countFields()
    {
        return $this->stmt->columnCount();
    }
    public function sql($params = [])
    {
        $sql = $this->stmt->queryString;
        if ($params) {
            $search = array_keys($params);
            $replace = array_values($params);
            array_walk($replace, function (&$param) {
                $param = '"' . $param . '"';
            });
            $sql = str_replace($search, $replace, $sql);
        }
        return $sql;
    }
    public function params()
    {
        return $this->stmt->debugDumpParams();
    }
}

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
    public function sql()
    {
        return $this->stmt->queryString;
    }
    public function params()
    {
        return $this->stmt->debugDumpParams();
    }
}

<?php

namespace Pheral\Essential\Storage\Database\Result;

use Pheral\Essential\Storage\Database\Query;

class QueryResult
{
    protected $query;
    protected $stmt;
    public function __construct(Query $query, \PDOStatement $stmt)
    {
        $this->query = $query;
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
    public function getSql($params = [])
    {
        return $this->query->getConnect()->getSql(
            $this->stmt->queryString,
            $params
        );
    }
    public function params()
    {
        return $this->stmt->debugDumpParams();
    }
}

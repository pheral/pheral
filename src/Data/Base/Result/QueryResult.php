<?php

namespace Pheral\Essential\Data\Base\Result;

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
}

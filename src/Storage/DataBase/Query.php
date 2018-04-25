<?php

namespace Pheral\Essential\Storage\DataBase;

use Pheral\Essential\Storage\DataBase\Result\InsertResult;
use Pheral\Essential\Storage\DataBase\Result\QueryResult;
use Pheral\Essential\Storage\DataBase\Result\SelectResult;

class Query extends QueryBuilder
{
    public function __construct($table = null, $alias = null)
    {
        if ($table) {
            $this->table($table, $alias);
        }
    }

    public function insert()
    {
        return new InsertResult(
            DB::execute($this->sqlInsert(), $this->getParams())
        );
    }

    public function update()
    {
        return new QueryResult(
            DB::execute($this->sqlUpdate(), $this->getParams())
        );
    }

    public function delete()
    {
        return new QueryResult(
            DB::execute($this->sqlDelete(), $this->getParams())
        );
    }

    /**
     * @param string|null $table
     * @param array $fields
     * @return \Pheral\Essential\Storage\DataBase\Result\SelectResult
     */
    public function select($table = null, $fields = [])
    {
        if ($table && !$this->getTable()) {
            $this->table($table);
        }
        if ($fields) {
            $this->fields($fields);
        }
        $result = new SelectResult(
            DB::execute($this->sqlSelect(), $this->getParams()),
            $table ?? $this->getDataName()
        );
        return $result;
    }
}

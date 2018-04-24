<?php

namespace Pheral\Essential\Data\Base;

use Pheral\Essential\Data\Base\Result\InsertResult;
use Pheral\Essential\Data\Base\Result\QueryResult;
use Pheral\Essential\Data\Base\Result\SelectResult;

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
     * @return \Pheral\Essential\Data\Base\Result\SelectResult
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

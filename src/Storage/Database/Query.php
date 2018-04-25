<?php

namespace Pheral\Essential\Storage\Database;

use Pheral\Essential\Storage\Database\Query\Builder;
use Pheral\Essential\Storage\Database\Result\InsertResult;
use Pheral\Essential\Storage\Database\Result\QueryResult;
use Pheral\Essential\Storage\Database\Result\SelectResult;

class Query extends Builder
{
    public function insert()
    {
        return new InsertResult(
            DB::execute($this->sqlInsert(), $this->getParams())
        );
    }

    /**
     * @param string|null $table
     * @param array $fields
     * @return \Pheral\Essential\Storage\Database\Result\SelectResult
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
}

<?php

namespace Pheral\Essential\Storage\Database;

use Pheral\Essential\Storage\Database\Query\Builder;
use Pheral\Essential\Storage\Database\Result\InsertResult;
use Pheral\Essential\Storage\Database\Result\QueryResult;
use Pheral\Essential\Storage\Database\Result\SelectResult;

class Query extends Builder
{
    public function insert($values)
    {
        if ($values && !$this->values) {
            $this->values($values);
        }
        return new InsertResult(
            DB::execute($this->sqlInsert(), $this->getParams())
        );
    }

    /**
     * @param string|null $dataTable
     * @return \Pheral\Essential\Storage\Database\Result\SelectResult
     */
    public function select($dataTable = null)
    {
        if ($dataTable && !$this->dataTable) {
            $this->dataTable($dataTable);
        }
        $result = new SelectResult(
            DB::execute($this->sqlSelect(), $this->getParams()),
            $dataTable ?? $this->getDataTable()
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

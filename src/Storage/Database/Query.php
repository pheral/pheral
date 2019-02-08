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
     * @param string $alias
     * @return \Pheral\Essential\Storage\Database\Result\SelectResult
     */
    public function select($dataTable = null, $alias = '')
    {
        if ($dataTable && !$this->dataTable) {
            $this->dataTable($dataTable, $alias);
        }
        $table = $dataTable ?? $this->getDataTable();
        $statement = DB::execute($this->sqlSelect(), $this->getParams());
        return new SelectResult($statement, $table, $this->relations);
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

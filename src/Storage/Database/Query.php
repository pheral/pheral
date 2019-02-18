<?php

namespace Pheral\Essential\Storage\Database;

use Pheral\Essential\Storage\Database\Query\Builder;
use Pheral\Essential\Storage\Database\Result\InsertResult;
use Pheral\Essential\Storage\Database\Result\QueryResult;
use Pheral\Essential\Storage\Database\Result\SelectResult;

class Query extends Builder
{
    public function execute($sql, $params = [])
    {
        $statement = $this->getConnection()->execute($sql, $params);
        return new QueryResult($this, $statement);
    }

    public function insert($values)
    {
        if ($values && !$this->values) {
            $this->values($values);
        }
        $statement = $this->getConnection()->execute(
            $this->sqlInsert(),
            $this->getParams()
        );
        return new InsertResult($this, $statement);
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
        $statement = $this->getConnection()->execute(
            $this->sqlSelect(),
            $this->getParams()
        );
        return new SelectResult($this, $statement, $table, $this->relations);
    }

    public function update()
    {
        return $this->execute(
            $this->sqlUpdate(),
            $this->getParams()
        );
    }

    public function delete()
    {
        return $this->execute(
            $this->sqlDelete(),
            $this->getParams()
        );
    }
}

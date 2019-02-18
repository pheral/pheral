<?php

namespace Pheral\Essential\Storage\Database\Result;

use Pheral\Essential\Layers\DataTable;
use Pheral\Essential\Storage\Database\Query;
use Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface;

class SelectResult extends QueryResult
{
    protected $dataTable;
    protected $relations = [];

    public function __construct(Query $query, \PDOStatement $stmt, $table = null, $relations = [])
    {
        parent::__construct($query, $stmt);
        if ($table && is_subclass_of($table, DataTable::class)) {
            $this->stmt->setFetchMode(\PDO::FETCH_CLASS, $table);
            $this->dataTable = $table;
        }
        $this->relations = $relations;
    }

    /**
     * @return \Pheral\Essential\Layers\DataTable|\stdClass|mixed
     */
    public function row()
    {
        if ($result = $this->stmt->fetch()) {
            $result = $this->applyRelations($result, true);
        }
        return $result;
    }

    /**
     * @return \Pheral\Essential\Layers\DataTable[]|\stdClass[]|array|mixed
     */
    public function all()
    {
        if ($result = $this->stmt->fetchAll()) {
            $result = $this->applyRelations($result, false);
        }
        return $result;
    }

    protected function applyRelations($result, $isOneRow = false)
    {
        if ($result && $this->dataTable && $this->relations) {
            $connection = $this->getQuery()->getConnection();
            $relations = call_user_func($this->dataTable . '::relations');
            foreach ($this->relations as $relationName => $relationData) {
                $relation = array_get($relations, $relationName);
                if ($relation instanceof RelationInterface) {
                    $targetRelations = [];
                    $targetConditions = null;
                    if (is_callable($relationData)) {
                        $targetConditions = $relationData;
                    } else {
                        $targetRelations = $relationData;
                    }
                    $result = $relation->setConnection($connection)
                        ->setHolder($this->dataTable, array_wrap($result))
                        ->setTargetRelations($targetRelations)
                        ->apply($relationName, $targetConditions);
                    if ($isOneRow) {
                        $result = array_shift($result);
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param string $field
     * @return \Pheral\Essential\Layers\DataTable[]|\stdClass[]|array|mixed
     */
    public function keyBy($field)
    {
        $result = [];
        $rows = $this->all();
        foreach ($rows as $row) {
            $result[$row->{$field}] = $row;
        }
        return $result;
    }
}

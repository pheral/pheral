<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

use Pheral\Essential\Layers\DataTable;
use Pheral\Essential\Storage\Database\DB;
use Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface;

abstract class RelationAbstract implements RelationInterface
{
    protected $targetRelations;

    protected function parseTableName($table)
    {
        return DB::tableName($table);
    }

    protected function parseTableClass($table)
    {
        if (is_subclass_of($table, DataTable::class)) {
            return $table;
        }
        return null;
    }

    protected function getResult($callable = null)
    {
        $query = $this->getQuery();
        if (is_callable($callable)) {
            $callable($query);
        }
        return $query->with($this->targetRelations)->select();
    }

    public function setTargetRelations($relations = [])
    {
        if ($relations) {
            $this->targetRelations = $relations;
        }
        return $this;
    }

    public function getRow($callable = null)
    {
        return $this->getResult($callable)->row();
    }

    public function getAll($callable = null)
    {
        return $this->getResult($callable)->all();
    }
}

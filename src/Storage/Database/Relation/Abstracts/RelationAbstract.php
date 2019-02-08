<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

use Pheral\Essential\Layers\DataTable;
use Pheral\Essential\Storage\Database\DB;
use Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface;

abstract class RelationAbstract implements RelationInterface
{
    protected $holderRows;
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

    public function setTargetRelations($relations = [])
    {
        if ($relations) {
            $this->targetRelations = $relations;
        }
        return $this;
    }
}

<?php

namespace Pheral\Essential\Storage\Database\Relation\Interfaces;

interface RelationInterface
{
    /**
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public function getQuery();

    /**
     * @param string $table
     * @param array $holderRows
     * @return \Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface|static
     */
    public function setHolder($table, $holderRows);

    /**
     * @param array $relations
     * @return \Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface|static
     */
    public function setTargetRelations($relations = []);

    /**
     * @param string $relationName
     * @param callable|null $callable
     * @return array
     */
    public function apply($relationName, $callable = null);
}

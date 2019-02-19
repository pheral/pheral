<?php

namespace Pheral\Essential\Storage\Database\Relation\Interfaces;

use Pheral\Essential\Storage\Database\Connection;

interface RelationInterface
{
    /**
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public function getQuery();

    /**
     * @param \Pheral\Essential\Storage\Database\Connection $connection
     * @return \Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface|static
     */
    public function setConnection(Connection $connection);

    /**
     * @return \Pheral\Essential\Storage\Database\Connection $connect
     */
    public function getConnection(): Connection;

    /**
     * @param string $table
     * @param array $holderRows
     * @return \Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface|static
     */
    public function setHolder(string $table, array $holderRows = []);

    /**
     * @param array $relations
     * @return \Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface|static
     */
    public function setTargetRelations($relations = []);

    /**
     * @param callable|null $callable
     * @return \Pheral\Essential\Storage\Database\DBTable|array
     */
    public function getRow($callable = null);

    /**
     * @param callable|null $callable
     * @return \Pheral\Essential\Storage\Database\DBTable[]|array
     */
    public function getAll($callable = null);

    /**
     * @param string $relationName
     * @param callable|null $callable
     * @return \Pheral\Essential\Storage\Database\DBTable[]|array
     */
    public function apply($relationName, $callable = null);
}

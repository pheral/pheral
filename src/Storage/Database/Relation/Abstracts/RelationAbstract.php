<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

use Pheral\Essential\Storage\Database\Connection;
use Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface;

abstract class RelationAbstract implements RelationInterface
{
    protected $connection;
    protected $targetRelations;

    public function setConnection(Connection $connection)
    {
        $this->connection = $connection;
        return $this;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
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

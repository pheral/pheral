<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

use Pheral\Essential\Storage\Database\Connect;
use Pheral\Essential\Storage\Database\DB;
use Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface;

abstract class RelationAbstract implements RelationInterface
{
    protected $connect;
    protected $targetRelations;

    public function setConnect(Connect $connect)
    {
        $this->connect = $connect;
        return $this;
    }

    public function getConnect(): Connect
    {
        if (!$this->connect) {
            $this->connect = DB::connect();
        }
        return $this->connect;
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

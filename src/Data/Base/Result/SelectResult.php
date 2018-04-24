<?php

namespace Pheral\Essential\Data\Base\Result;

use Pheral\Essential\Data\Base\Entity;

class SelectResult extends QueryResult
{
    protected $entity;

    public function __construct($stmt, $entity = null)
    {
        parent::__construct($stmt);
        if ($entity && is_subclass_of($entity, Entity::class)) {
            $this->stmt->setFetchMode(\PDO::FETCH_CLASS, $entity);
        }
    }

    public function all()
    {
        return $this->stmt->fetchAll();
    }

    public function row()
    {
        return $this->stmt->fetch();
    }
}

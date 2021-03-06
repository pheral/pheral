<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

abstract class TwoTableRelationAbstract extends OneTableRelationAbstract
{
    protected $target;
    protected $targetKey;
    protected $targetKeyToPivot;
    protected $targetKeyToHolder;

    /**
     * @param string $target
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\TwoTableRelationAbstract|static
     */
    protected function setTarget(string $target)
    {
        $this->target = $target;
        return $this;
    }

    protected function getTarget(): string
    {
        $connection = $this->getConnection();
        if (!$target = $connection->getTableClass($this->target)) {
            $target = $connection->getTableName($this->target);
        }
        return $target;
    }

    /**
     * @param string $keyName
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\TwoTableRelationAbstract|static
     */
    protected function setTargetKey($keyName)
    {
        $this->targetKey = $keyName;
        return $this;
    }

    /**
     * @param string $keyName
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\TwoTableRelationAbstract|static
     */
    protected function setTargetKeyToPivot($keyName)
    {
        $this->targetKeyToPivot = $keyName;
        return $this;
    }

    /**
     * @param string $keyName
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\TwoTableRelationAbstract|static
     */
    protected function setTargetKeyToHolder($keyName)
    {
        $this->targetKeyToHolder = $keyName;
        return $this;
    }
}

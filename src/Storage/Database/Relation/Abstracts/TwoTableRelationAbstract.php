<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

abstract class TwoTableRelationAbstract extends OneTableRelationAbstract
{
    protected $targetClass;
    protected $targetTable;
    protected $targetKey;
    protected $targetKeyToPivot;
    protected $targetKeyToHolder;

    /**
     * @param string $table
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\TwoTableRelationAbstract|static
     */
    protected function setTargetTable($table)
    {
        $this->targetTable = $this->parseTableName($table);
        $this->targetClass = $this->parseTableClass($table);
        return $this;
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

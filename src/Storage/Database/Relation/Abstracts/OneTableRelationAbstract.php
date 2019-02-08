<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

abstract class OneTableRelationAbstract extends RelationAbstract
{
    protected $holderClass;
    protected $holderTable;
    protected $holderKey;
    protected $holderKeyToTarget;
    protected $holderKeyToPivot;

    /**
     * @param string $table
     * @param array $holderRows
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\OneTableRelationAbstract|static
     */
    public function setHolder($table, $holderRows)
    {
        $this->setHolderTable($table)
            ->setHolderRows($holderRows);
        return $this;
    }

    /**
     * @param array $holderRows
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\OneTableRelationAbstract|static
     */
    protected function setHolderRows($holderRows)
    {
        $this->holderRows = $holderRows;
        return $this;
    }

    /**
     * @param string $table
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\OneTableRelationAbstract|static
     */
    protected function setHolderTable($table)
    {
        $this->holderTable = $this->parseTableName($table);
        $this->holderClass = $this->parseTableClass($table);
        return $this;
    }

    /**
     * @param string $keyName
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\OneTableRelationAbstract|static
     */
    protected function setHolderKey($keyName)
    {
        $this->holderKey = $keyName;
        return $this;
    }

    /**
     * @param string $keyName
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\OneTableRelationAbstract|static
     */
    protected function setHolderKeyToPivot($keyName)
    {
        $this->holderKeyToPivot = $keyName;
        return $this;
    }

    /**
     * @param string $keyName
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\OneTableRelationAbstract|static
     */
    protected function setHolderKeyToTarget($keyName)
    {
        $this->holderKeyToTarget = $keyName;
        return $this;
    }
}

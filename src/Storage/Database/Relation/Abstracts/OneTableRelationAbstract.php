<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

abstract class OneTableRelationAbstract extends RelationAbstract
{
    protected $holderRows;

    protected $holder;
    protected $holderKey;
    protected $holderKeyToTarget;
    protected $holderKeyToPivot;

    /**
     * @param string $holder
     * @param array $holderRows
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\OneTableRelationAbstract|static
     */
    public function setHolder($holder, $holderRows)
    {
        $this->holder = $holder;
        $this->setHolderRows($holderRows);
        return $this;
    }

    protected function getHolder()
    {
        $connection = $this->getConnection();
        if (!$holder = $connection->getTableClass($this->holder)) {
            $holder = $connection->getTableName($this->holder);
        }
        return $holder;
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

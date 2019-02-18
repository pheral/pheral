<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

abstract class ThreeTableRelationAbstract extends TwoTableRelationAbstract
{
    protected $pivot;
    protected $pivotKey;
    protected $pivotKeyToTarget;
    protected $pivotKeyToHolder;

    /**
     * @param string $pivot
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\ThreeTableRelationAbstract|static
     */
    protected function setPivot($pivot)
    {
        $this->pivot = $pivot;
        return $this;
    }

    protected function pivotTable()
    {
        return $this->getConnection()->getTableName($this->pivot);
    }

    protected function getPivot()
    {
        $connection = $this->getConnection();
        if (!$pivot = $connection->getTableClass($this->pivot)) {
            $pivot = $connection->getTableName($this->pivot);
        }
        return $pivot;
    }

    /**
     * @param string $keyName
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\ThreeTableRelationAbstract|static
     */
    protected function setPivotKey($keyName)
    {
        $this->pivotKey = $keyName;
        return $this;
    }

    /**
     * @param string $keyName
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\ThreeTableRelationAbstract|static
     */
    protected function setPivotKeyToTarget($keyName)
    {
        $this->pivotKeyToTarget = $keyName;
        return $this;
    }

    /**
     * @param string $keyName
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\ThreeTableRelationAbstract|static
     */
    protected function setPivotKeyToHolder($keyName)
    {
        $this->pivotKeyToHolder = $keyName;
        return $this;
    }
}

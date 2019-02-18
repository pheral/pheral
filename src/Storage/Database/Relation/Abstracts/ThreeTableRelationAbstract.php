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
        return $this->getConnect()->getTableName($this->pivot);
    }

    protected function getPivot()
    {
        $connect = $this->getConnect();
        if (!$pivot = $connect->getTableClass($this->pivot)) {
            $pivot = $connect->getTableName($this->pivot);
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

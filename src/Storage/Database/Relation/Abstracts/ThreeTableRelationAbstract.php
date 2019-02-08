<?php

namespace Pheral\Essential\Storage\Database\Relation\Abstracts;

abstract class ThreeTableRelationAbstract extends TwoTableRelationAbstract
{
    protected $pivotClass;
    protected $pivotTable;
    protected $pivotKey;
    protected $pivotKeyToTarget;
    protected $pivotKeyToHolder;

    /**
     * @param string $table
     * @return \Pheral\Essential\Storage\Database\Relation\Abstracts\ThreeTableRelationAbstract|static
     */
    protected function setPivotTable($table)
    {
        $this->pivotTable = $this->parseTableName($table);
        $this->pivotClass = $this->parseTableClass($table);
        return $this;
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

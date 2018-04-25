<?php

namespace Pheral\Essential\Storage\Database\Query;

use Pheral\Essential\Storage\Database\Query\Traits\Getter;
use Pheral\Essential\Storage\Database\Query\Traits\Setter;

class Builder
{
    use Getter, Setter;

    protected $dataTable;

    protected $distinct = false;
    protected $holders = [];
    protected $params = [];

    protected $fields = [];
    protected $tables = [];
    protected $joins = [];
    protected $wheres = [];
    protected $groups = [];
    protected $having = [];
    protected $orders = [];
    protected $limit;
    protected $offset;

    public function __construct($dataTable = null, $alias = null)
    {
        if ($dataTable) {
            $this->dataTable($dataTable, $alias);
        }
    }

    public function sqlInsert($withParams = false)
    {
        $sql = 'INSERT '
            . $this->getInto()
            . $this->getSets()
            . $this->getValues()
            . $this->getOnDuplicateKey();

        return $this->makeSql($sql, $withParams);
    }

    public function sqlUpdate($withParams = false)
    {
        $sql = 'UPDATE '
            . $this->getTable()
            . $this->getSets()
            . $this->getWhere()
            . $this->getOrderBy()
            . $this->getLimit();

        return $this->makeSql($sql, $withParams);
    }

    public function sqlDelete($withParams = false)
    {
        $sql = 'DELETE '
            . $this->getFields()
            . $this->getFrom()
            . $this->getUsing()
            . $this->getWhere();

        return $this->makeSql($sql, $withParams);
    }

    public function sqlSelect($withParams = false)
    {
        $sql = 'SELECT '
            . $this->getDistinct()
            . $this->getFields()
            . $this->getFrom()
            . $this->getJoin()
            . $this->getWhere()
            . $this->getGroupBy()
            . $this->getHaving()
            . $this->getOrderBy()
            . $this->getLimit()
            . $this->getOffset();

        return $this->makeSql($sql, $withParams);
    }

    protected function makeSql($sql, $withParams)
    {
        if ($withParams) {
            $sql = $this->fillParams($sql, $this->getParams());
        }
        return trim($sql);
    }

    protected function fillParams($sql, $params)
    {
        $search = array_keys($params);
        $replace = array_values($params);
        array_walk($replace, function (&$param) {
            $param = '"' . $param . '"';
        });
        return str_replace($search, $replace, $sql);
    }
}

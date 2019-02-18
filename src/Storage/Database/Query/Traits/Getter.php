<?php

namespace Pheral\Essential\Storage\Database\Query\Traits;

use Pheral\Essential\Storage\Database\Connect;

trait Getter
{
    public function getParams()
    {
        return $this->params;
    }

    protected function getDataTable()
    {
        return $this->dataTable;
    }

    protected function getDistinct()
    {
        if ($this->distinct) {
            return 'DISTINCT ';
        }
        return '';
    }

    protected function getFields()
    {
        if (!$fields = array_wrap($this->fields)) {
            $fields = ['*'];
        }
        return implode(', ', $fields) . ' ';
    }

    protected function getTables()
    {
        if ($tables = array_wrap($this->tables)) {
            return implode(', ', $tables) . ' ';
        }
        return '';
    }

    protected function getInto()
    {
        if ($tables = $this->getTables()) {
            $sql = 'INTO '. $tables;
            if ($fields = array_wrap($this->fields)) {
                $sql .= '(' . implode(', ', $fields) . ') ';
            }
            return $sql;
        }
        return '';
    }

    protected function getFrom()
    {
        if ($tables = $this->getTables()) {
            return 'FROM ' . $tables;
        }
        return '';
    }

    protected function getJoin()
    {
        if ($joins = $this->joins) {
            return implode(' ', $joins) . ' ';
        }
        return '';
    }

    protected function getUsing()
    {
        $using = '';
        return $using;
    }

    protected function getWhere()
    {
        if ($wheres = array_wrap($this->wheres)) {
            return 'WHERE ' . implode(' ', $wheres) . ' ';
        }
        return '';
    }

    protected function getGroupBy()
    {
        if ($groups = $this->groups) {
            return 'GROUP BY ' . implode(', ', $groups) . ' ';
        }
        return '';
    }

    protected function getHaving()
    {
        if ($having = array_wrap($this->having)) {
            return 'HAVING ' . implode(' ', $having) . ' ';
        }
        return '';
    }

    protected function getOrderBy()
    {
        if ($orders = $this->orders) {
            return 'ORDER BY ' . implode(', ', $orders) . ' ';
        }
        return '';
    }

    protected function getLimit()
    {
        if ($limit = $this->limit) {
            return 'LIMIT ' . $limit . ' ';
        }
        return '';
    }

    protected function getOffset()
    {
        if ($offset = $this->offset) {
            return 'OFFSET ' . $offset . ' ';
        }
        return '';
    }

    protected function getSets()
    {
        if ($sets = $this->sets) {
            return 'SET ' . implode(', ', $sets) . ' ';
        }
        return '';
    }

    protected function getValues()
    {
        if ($values = $this->values) {
            return 'VALUES ' . implode(', ', $values) . ' ';
        }
        return $values;
    }

    public function getConnect(): Connect
    {
        return $this->connect;
    }
}

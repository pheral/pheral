<?php

namespace Pheral\Essential\Storage\Database\Query\Traits;

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

    protected function getTable()
    {
        if ($tables = array_wrap($this->tables)) {
            return implode(', ', $tables);
        }
        return '';
    }

    protected function getInto()
    {
        if ($table = $this->getTable()) {
            $sql = 'INTO '. $table;
            if ($fields = array_wrap($this->fields)) {
                $sql .= ' (' . implode(', ', $fields) . ')';
            }
            return $sql . ' ';
        }
        return '';
    }

    protected function getFrom()
    {
        if ($table = $this->getTable()) {
            return 'FROM ' . $table . ' ';
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
        $sets = '';
        return $sets;
    }

    protected function getValues()
    {
        if ($values = $this->values) {
            return 'VALUES ' . implode(', ', $values) . ' ';
        }
        return $values;
    }
}

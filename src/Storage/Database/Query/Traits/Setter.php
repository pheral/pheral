<?php

namespace Pheral\Essential\Storage\Database\Query\Traits;

use Pheral\Essential\Storage\Database\Connect;

trait Setter
{
    /**
     * @param \Pheral\Essential\Storage\Database\Connect $connect
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    protected function setConnect(Connect $connect)
    {
        $this->connect = $connect;
        return $this;
    }

    /**
     * @param string $field
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function distinct($field = '')
    {
        if ($field) {
            $this->fields([$field]);
        }
        $this->distinct = true;
        return $this;
    }

    /**
     * @param array $fields
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function fields($fields = [])
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param array $fields
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function fieldsAdd($fields = [])
    {
        if (!$this->fields) {
            $this->fields(['*']);
        }
        return $this->fields(array_merge($this->fields, $fields));
    }

    /**
     * @param string $table
     * @param string $alias
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    protected function addTable($table, $alias)
    {
        if ($table && !in_array($table, $this->tables)) {
            $this->tables[] = $table . $this->makeAlias($alias);
        }
        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    protected function dataTable($table, $alias = '')
    {
        if (!$this->dataTable) {
            $this->dataTable = $table;
        }
        return $this->addTable($this->getTableName($table), $alias);
    }

    /**
     * @param string $table
     * @param string $alias
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function table($table, $alias = '')
    {
        return $this->addTable($this->getTableName($table), $alias);
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $expression
     * @param array $wheres
     * @param string $type
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function join($table, $alias, $expression, $wheres = [], $type = 'INNER')
    {
        $where = '';
        if ($wheres) {
            $parts = [];
            foreach ($wheres as $where) {
                $count = count($wheres);
                if ($count > 3) {
                    list($field, $operator, $value, $type) = $where;
                } elseif ($count > 2) {
                    list($field, $operator, $value) = $where;
                    $type = 'AND';
                } else {
                    list($field, $value) = $where;
                    $operator = '=';
                    $type = 'AND';
                }
                $holder = $this->makeHolder($field);
                $this->params[$holder] = $value;
                $parts[] = $type . ' ' . $field . ' ' . $operator . ' ' . $holder;
            }
            $where = ' ' . implode(' ', $parts);
        }
        $tableName = $this->getTableName($table) . $this->makeAlias($alias);
        $this->joins[$tableName] = strtoupper($type) . ' JOIN ' . $tableName . ' ON ' . $expression . $where;
        return $this;
    }

    /**
     * @param string $table
     * @param string $alias
     * @param string $expression
     * @param array $wheres
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function leftJoin($table, $alias, $expression, $wheres = [])
    {
        return $this->join($table, $alias, $expression, $wheres, 'LEFT');
    }

    /**
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @param string $type
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function where($field, $operator = '=', $value = null, $type = 'AND')
    {
        if (is_null($value)) {
            $nullOperator = in_array($operator, ['=', 'IS']) ? 'IS' : 'IS NOT';
            return $this->whereConst($field, $nullOperator, $type);
        }
        $holder = $this->makeHolder($field);
        $this->wheres[] = $this->makeType($this->wheres, $type) . $field . ' ' . $operator . ' ' . $holder;
        $this->params[$holder] = $value;
        return $this;
    }

    /**
     * @param string $field
     * @param string $operator
     * @param null $value
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function orWhere($field, $operator = '=', $value = null)
    {
        return $this->where($field, $operator, $value, 'OR');
    }

    /**
     * @param $field
     * @param string $operator
     * @param array $values
     * @param string $type
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    protected function whereList($field, $operator = 'IN', $values = [], $type = 'AND')
    {
        $holders = [];
        foreach ($values as $value) {
            $holder = $this->makeHolder($field);
            $holders[] = $holder;
            $this->params[$holder] = $value;
        }
        $values = '(' . implode(',', $holders) . ')';
        $this->wheres[] = $this->makeType($this->wheres, $type) . $field . ' ' . $operator . ' ' . $values;
        return $this;
    }

    /**
     * @param $field
     * @param $values
     * @param string $type
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function whereIn($field, $values, $type = 'AND')
    {
        return $this->whereList($field, 'IN', $values, $type);
    }

    /**
     * @param $field
     * @param $values
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function orWhereIn($field, $values)
    {
        return $this->whereIn($field, $values, 'OR');
    }

    /**
     * @param $field
     * @param $values
     * @param string $type
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function whereNotIn($field, $values, $type = 'AND')
    {
        return $this->whereList($field, 'NOT IN', $values, $type);
    }

    /**
     * @param $field
     * @param $values
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function orWhereNotIn($field, $values)
    {
        return $this->whereNotIn($field, $values, 'OR');
    }

    /**
     * @param $field
     * @param string $operator
     * @param string $type
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    protected function whereConst($field, $operator = 'IS', $type = 'AND')
    {
        $this->wheres[] = $this->makeType($this->wheres, $type) . $field . ' ' . $operator . ' NULL';
        return $this;
    }

    /**
     * @param $field
     * @param string $type
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function whereNull($field, $type = 'AND')
    {
        return $this->whereConst($field, 'IS', $type);
    }

    /**
     * @param $field
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function orWhereNull($field)
    {
        return $this->whereNull($field, 'OR');
    }

    /**
     * @param $field
     * @param string $type
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function whereNotNull($field, $type = 'AND')
    {
        return $this->whereConst($field, 'IS NOT', $type);
    }

    /**
     * @param $field
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function orWhereNotNull($field)
    {
        return $this->whereNotNull($field, 'OR');
    }

    /**
     * @param $field
     * @param string $direction
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function groupBy($field, $direction = null)
    {
        $this->groups[] = $field . ($direction ? ' ' . $direction : '');
        return $this;
    }

    /**
     * @param $field
     * @param string $operator
     * @param null $value
     * @param string $type
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function having($field, $operator = '=', $value = null, $type = 'AND')
    {
        $holder = $this->makeHolder($field);
        $this->having[] = $this->makeType($this->having, $type) . $field . ' ' . $operator . ' ' . $holder;
        $this->params[$holder] = $value;
        return $this;
    }

    /**
     * @param $field
     * @param string $operator
     * @param null $value
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function orHaving($field, $operator = '=', $value = null)
    {
        return $this->having($field, $operator, $value, 'OR');
    }

    /**
     * @param $field
     * @param null $direction
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function orderBy($field, $direction = null)
    {
        $this->orders[] = $field . ($direction ? ' ' . $direction : '');
        return $this;
    }

    /**
     * @param int $limit
     * @param null $offset
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function limit($limit, $offset = null)
    {
        $this->limit = $limit;
        if (is_int($offset)) {
            $this->offset($offset);
        }
        return $this;
    }

    /**
     * @param int $offset
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function sets($sets = [])
    {
        foreach ($sets as $field => $value) {
            $holder = $this->makeHolder($field);
            $this->sets[] = $field . ' = ' . $holder;
            $this->params[$holder] = $value;
        }
        return $this;
    }

    /**
     * @param array $values
     * @param array $onlyFields
     * @return \Pheral\Essential\Storage\Database\Query|static
     */
    public function values($values = [], $onlyFields = [])
    {
        $holders = [];
        $foundedFields = [];
        foreach ($values as $field => $value) {
            $holder = $this->makeHolder($field);
            $holders[] = $holder;
            $this->params[$holder] = $value;
            $foundedFields[] = $field;
        }
        $this->values[] = '(' . implode(', ', $holders) . ')';

        if (!$this->fields) {
            $this->fields($onlyFields ? $onlyFields : $foundedFields);
        }

        return $this;
    }

    public function with($relations)
    {
        $this->relations = array_merge_recursive($this->relations, $this->makeRelationsTree($relations));
        return $this;
    }

    protected function makeRelationsTree($relations)
    {
        $relationsTree = [];
        $relations = array_wrap($relations);
        foreach ($relations as $relationKey => $relationValue) {
            if (!is_numeric($relationKey) && is_string($relationKey)) {
                $relationName = $relationKey;
            } elseif (is_string($relationValue)) {
                $relationName = $relationValue;
                $relationValue = null;
            } else {
                continue;
            }
            if (is_array($relationValue)) {
                $relationValue = $this->makeRelationsTree($relationValue);
            }
            $relationsTree[$relationName] = $relationValue;
        }
        return $relationsTree;
    }

    protected function makeAlias($alias)
    {
        return $alias ? ' AS ' . $alias : '';
    }

    protected function makeHolder($field)
    {
        $field = string_end($field);
        $holder = string_snake_case($field);
        if (!array_has($this->holders, $holder)) {
            $this->holders[$holder] = 0;
        }
        $this->holders[$holder] += 1;
        return ':' . $holder . $this->holders[$holder];
    }

    protected function makeType($list, $type)
    {
        return (!empty($list) ? $type . ' ' : '');
    }

    protected function getTableName($table)
    {
        if ($this->connect instanceof Connect) {
            return $this->connect->getTableName($table);
        }
        return '';
    }
}

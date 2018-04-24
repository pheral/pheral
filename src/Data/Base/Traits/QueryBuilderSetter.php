<?php

namespace Pheral\Essential\Data\Base\Traits;

use Pheral\Essential\Data\Base\Entity;

trait QueryBuilderSetter
{
    /**
     * @param string $field
     * @return \Pheral\Essential\Data\Base\Query|static
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
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function fields($fields = [])
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @param array $fields
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function fieldsAdd($fields = [])
    {
        return $this->fields(array_merge($this->fields, $fields));
    }

    /**
     * @param string $entity
     * @param string $alias
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function table($entity, $alias = '')
    {
        $table = $this->makeTable($entity, true);
        if (!in_array($table, $this->tables)) {
            $this->tables[] = $table . $this->makeAlias($alias);
        }
        return $this;
    }

    /**
     * @param string $entity
     * @param string $alias
     * @param string $expression
     * @param string $type
     * @param array $wheres
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function join($entity, $alias, $expression, $type = 'inner', $wheres = [])
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
        $table = $this->makeTable($entity) . $this->makeAlias($alias);
        $this->joins[$table] = strtoupper($type) . ' JOIN ' . $table . ' ON ' . $expression . $where;
        return $this;
    }

    /**
     * @param string $field
     * @param string $operator
     * @param mixed $value
     * @param string $type
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function where($field, $operator = '=', $value = null, $type = 'AND')
    {
        if (is_null($value)) {
            $nullOperator = in_array($operator, ['!=', 'NOT', 'IS NOT']) ? 'IS NOT' : 'IS';
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
     * @return \Pheral\Essential\Data\Base\Query|static
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
     * @return \Pheral\Essential\Data\Base\Query|static
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
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function whereIn($field, $values, $type = 'AND')
    {
        return $this->whereList($field, 'IN', $values, $type);
    }

    /**
     * @param $field
     * @param $values
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function orWhereIn($field, $values)
    {
        return $this->whereIn($field, $values, 'OR');
    }

    /**
     * @param $field
     * @param $values
     * @param string $type
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function whereNotIn($field, $values, $type = 'AND')
    {
        return $this->whereList($field, 'NOT IN', $values, $type);
    }

    /**
     * @param $field
     * @param $values
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function orWhereNotIn($field, $values)
    {
        return $this->whereNotIn($field, $values, 'OR');
    }

    /**
     * @param $field
     * @param string $operator
     * @param string $type
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    protected function whereConst($field, $operator = 'IS', $type = 'AND')
    {
        $this->wheres[] = $this->makeType($this->wheres, $type) . $field . ' ' . $operator . ' NULL';
        return $this;
    }

    /**
     * @param $field
     * @param string $type
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function whereNull($field, $type = 'AND')
    {
        return $this->whereConst($field, 'IS', $type);
    }

    /**
     * @param $field
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function orWhereNull($field)
    {
        return $this->whereNull($field, 'OR');
    }

    /**
     * @param $field
     * @param string $type
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function whereNotNull($field, $type = 'AND')
    {
        return $this->whereConst($field, 'IS NOT', $type);
    }

    /**
     * @param $field
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function orWhereNotNull($field)
    {
        return $this->whereNotNull($field, 'OR');
    }

    /**
     * @param $field
     * @param string $direction
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function groupBy($field, $direction = 'ASC')
    {
        $this->groups[] = $field . ($direction ? ' ' . $direction : '');
        return $this;
    }

    /**
     * @param $field
     * @param string $operator
     * @param null $value
     * @param string $type
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function having($field, $operator = '=', $value = null, $type = 'AND')
    {
        $holder = $this->makeHolder($field);
        $this->having[] = $this->makeType($this->having, $type) . $field . $operator . $holder;
        $this->params[$holder] = $value;
        return $this;
    }

    /**
     * @param $field
     * @param string $operator
     * @param null $value
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function orHaving($field, $operator = '=', $value = null)
    {
        return $this->having($field, $operator, $value, 'OR');
    }

    /**
     * @param $field
     * @param null $direction
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function orderBy($field, $direction = null)
    {
        $this->orders[] = $field . ($direction ? ' ' . $direction : '');
        return $this;
    }

    /**
     * @param int $limit
     * @param null $offset
     * @return \Pheral\Essential\Data\Base\Query|static
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
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    protected function makeAlias($alias)
    {
        return $alias ? ' AS ' . $alias : '';
    }

    protected function makeHolder($field)
    {
        if (!array_has($this->holders, $field)) {
            $this->holders[$field] = 0;
        }
        $this->holders[$field] += 1;
        return ':' . $field . $this->holders[$field];
    }

    protected function makeTable($entity, $forceEntity = false)
    {
        if (is_subclass_of($entity, Entity::class)) {
            $table = string_snake_case(object_name($entity));
            if ($forceEntity && !$this->entity) {
                $this->entity = $entity;
            }
        } elseif (strpos($entity, '\\', true) !== false) {
            $table = '';
        } else {
            $table = $entity;
        }
        return $table;
    }
    protected function makeType($list, $type)
    {
        return (!empty($list) ? $type . ' ' : '');
    }
}

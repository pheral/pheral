<?php

namespace Pheral\Essential\Data\Base;

use \PDO;

class Query
{
    protected $entity;

    protected $table;

    protected $select = [];

    protected $from;

    protected $wheres = [];

    protected $params = [];

    public function __construct($entity = '')
    {
        if ($entity) {
            $this->setEntity($entity);
        }
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getTable()
    {
        if ($this->table) {
            return $this->table;
        }
        $this->table = string_snake_case(object_name($this->getEntity()));
        return $this->table;
    }

    public function get()
    {
        $stmt = DB::prepare($this->getSql(), $this->params);

        $entity = $this->getEntity();
        if ($entity && is_subclass_of($entity, Entity::class)) {
            return $stmt->fetchAll(PDO::FETCH_CLASS, $entity);
        }

        return $stmt->fetchAll();
    }

    /**
     * @param array $fields
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function select($fields = [])
    {
        if (!$fields) {
            $fields = '*';
        }
        $this->select = $fields;
        return $this;
    }

    /**
     * @param string $table
     * @return \Pheral\Essential\Data\Base\Query|static
     */
    public function from($table = '')
    {
        if (!$table) {
            $table = $this->getTable();
        }
        $this->from = $table;
        return $this;
    }

    public function where($field, $operator = '', $value = null)
    {
        $placeholder = ':' . $field;
        $this->wheres[] = $field . $operator . $placeholder;
        $this->params[$placeholder] = $value;
        return $this;
    }

    public function getSql()
    {
        $select = $this->getSelect();
        $from = $this->getFrom();
        $wheres = $this->getWheres();
        $query = $select . ' ' . $from
            . ($wheres ? ' ' . $wheres : '');
        return $query;
    }

    protected function getWheres()
    {
        if (!$wheres = $this->wheres) {
            return '';
        }

        return 'WHERE ' . implode(' AND ', $wheres);
    }

    protected function getSelect()
    {
        if (!$select = array_wrap($this->select)) {
            $select = ['*'];
        }
        $fields = [];
        foreach ($select as $key => $field) {
            if (!is_numeric($key)) {
                $fields[] = $key .'.' . $field;
            } else {
                $fields[] = $field;
            }
        }
        return 'SELECT ' . implode(', ', $fields);
    }

    protected function getFrom()
    {
        if (!$table = $this->from) {
            $table = $this->getTable();
        }
        return 'FROM ' . $table;
    }
}

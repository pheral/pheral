<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\DB;
use Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface;
use Pheral\Essential\Validation\TypeManager;

abstract class DataTable
{
    protected static $scheme = [];
    protected static $required = [];
    protected $enclosed = [];
    public function __construct(array $params = [])
    {
        if ($params) {
            foreach ($params as $field => $value) {
                $this->{$field} = $value;
            }
        }
    }
    public function __clone()
    {
        if ($this->enclosed) {
            foreach ($this->enclosed as $key => $value) {
                if (is_object($value)) {
                    $this->enclosed[$key] = clone $value;
                }
                if (is_array($value) || $value instanceof \ArrayAccess) {
                    foreach ($value as $nestedKey => $nestedValue) {
                        if (is_object($nestedValue)) {
                            $this->enclosed[$key][$nestedKey] = clone $nestedValue;
                        }
                    }
                }
            }
        }
    }
    public function __set($field, $value)
    {
        if (!$type = (static::$scheme[$field] ?? null)) {
            $this->enclosed[$field] = $value;
            return ;
        }
        if (TypeManager::validate($type, $value)) {
            $validValue = TypeManager::convert($type, $value);
        } elseif (isset(static::$required[$field])) {
            return;
        }
        $this->{$field} = $validValue ?? null;
    }
    public function __get($field)
    {
        return array_get($this->enclosed, $field);
    }

    /**
     * @param string $alias
     * @param string $connectionName
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public static function query($alias = '', $connectionName = '')
    {
        return static::connection($connectionName)->query(static::class, $alias);
    }

    /**
     * @param string|null $connectionName
     * @return \Pheral\Essential\Storage\Database\Connection
     */
    public static function connection(string $connectionName = null)
    {
        return DB::connection($connectionName);
    }

    /**
     * @return \Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface[]|array
     */
    public static function relations()
    {
        return [];
    }

    /**
     * @param string $relationName
     * @param string|null $connectionName
     * @return \Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface|null
     */
    public function relation($relationName, string $connectionName = null)
    {
        $relation = array_get(static::relations(), $relationName);
        if ($relation instanceof RelationInterface) {
            $relation->setConnection(static::connection($connectionName))
                ->setHolder(static::class, [$this]);
        }
        return $relation;
    }

    public static function makeRows($data, $dataTable = null)
    {
        $list = [];
        foreach ($data as $index => $row) {
            $list[$index] = static::makeRow($row, $dataTable);
        }
        return $list;
    }

    /**
     * @param $data
     * @param null $dataTable
     * @return \Pheral\Essential\Layers\DataTable|static
     */
    public static function makeRow($data, $dataTable = null)
    {
        if (is_subclass_of($dataTable, DataTable::class)) {
            return new $dataTable((array)$data);
        }
        return new static((array)$data);
    }
}

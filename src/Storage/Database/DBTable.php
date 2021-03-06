<?php

namespace Pheral\Essential\Storage\Database;

use Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface;
use Pheral\Essential\Validation\TypeManager;

abstract class DBTable
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
     * @return \Pheral\Essential\Storage\Database\Query
     */
    public static function query($alias = '')
    {
        return DB::connection()->query(static::class, $alias);
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
     * @return \Pheral\Essential\Storage\Database\Relation\Interfaces\RelationInterface|null
     */
    public function relation($relationName)
    {
        $relation = array_get(static::relations(), $relationName);
        if ($relation instanceof RelationInterface) {
            $relation->setConnection(DB::connection())
                ->setHolder(static::class, [$this]);
        }
        return $relation;
    }

    public static function makeRows($data, string $dbTable = null)
    {
        $list = [];
        foreach ($data as $index => $row) {
            $list[$index] = static::makeRow($row, $dbTable);
        }
        return $list;
    }

    /**
     * @param $data
     * @param null $dbTable
     * @return \Pheral\Essential\Storage\Database\DBTable|static
     */
    public static function makeRow($data, string $dbTable = null)
    {
        if (is_subclass_of($dbTable, DBTable::class)) {
            return new $dbTable((array)$data);
        }
        return new static((array)$data);
    }
}

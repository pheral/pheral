<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\Query;
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

    public static function query($alias = '')
    {
        return new Query(static::class, $alias);
    }
    public static function relations()
    {
        return [];
    }
    public static function makeList($data, $dataTable = null)
    {
        $list = [];
        foreach ($data as $index => $row) {
            $list[$index] = static::make($row, $dataTable);
        }
        return $list;
    }

    /**
     * @param $data
     * @param null $dataTable
     * @return \Pheral\Essential\Layers\DataTable|static
     */
    public static function make($data, $dataTable = null)
    {
        if (is_subclass_of($dataTable, DataTable::class)) {
            return new $dataTable((array)$data);
        }
        return new static((array)$data);
    }
}

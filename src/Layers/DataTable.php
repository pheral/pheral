<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\Query;
use Pheral\Essential\Validation\TypeManager;

abstract class DataTable
{
    protected static $scheme = [];
    protected static $required = [];
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
            return ;
        }
        if (TypeManager::validate($type, $value)) {
            $validValue = TypeManager::convert($type, $value);
        } elseif (isset(static::$required[$field])) {
            return;
        }
        $this->{$field} = $validValue ?? null;
    }
    public static function query($alias = '')
    {
        return new Query(static::class, $alias);
    }
}

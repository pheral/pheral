<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\Query;

abstract class DataTable
{
    public static function query($alias = '')
    {
        return new Query(static::class, $alias);
    }
}

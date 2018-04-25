<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\DB;

abstract class Data
{
    public static function query($alias = '')
    {
        return DB::query(static::class, $alias);
    }
}

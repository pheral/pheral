<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\DataBase\DB;
use Pheral\Essential\Storage\DataBase\Query;

abstract class Data
{
    public static function query($alias = ''): Query
    {
        return DB::query(static::class, $alias);
    }
}

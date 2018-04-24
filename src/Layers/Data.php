<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Data\Base\DB;
use Pheral\Essential\Data\Base\Query;

abstract class Data
{
    public static function query($alias = ''): Query
    {
        return DB::query(static::class, $alias);
    }
}

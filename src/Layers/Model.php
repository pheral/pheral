<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Data\Base\DB;
use Pheral\Essential\Data\Base\Query;

abstract class Model
{
    public static function query($entity = '', $alias = ''): Query
    {
        return DB::query($entity, $alias);
    }
    public function newQuery($entity = '', $alias = ''): Query
    {
        return static::query($entity, $alias);
    }
}

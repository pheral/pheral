<?php

namespace Pheral\Essential\Data\Base;

abstract class Entity
{
    public static function query($alias = ''): Query
    {
        return DB::query(static::class, $alias);
    }
    public function newQuery($alias = ''): Query
    {
        return static::query($alias);
    }
}

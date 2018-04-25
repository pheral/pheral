<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\DataBase\DB;
use Pheral\Essential\Storage\DataBase\Query;

abstract class Model
{
    public function newQuery($table = '', $alias = ''): Query
    {
        return DB::query($table, $alias);
    }
}

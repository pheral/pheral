<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\DB;

abstract class Model
{
    public function newQuery($table = '', $alias = '')
    {
        return DB::query($table, $alias);
    }
}

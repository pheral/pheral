<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\DB;

abstract class Model
{
    public function newQuery(string $dbTable = null, string $alias = null)
    {
        return DB::query($dbTable, $alias);
    }
}

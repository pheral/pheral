<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\DB;

abstract class Model
{
    public function newQuery($dataTable = '', $alias = '')
    {
        return DB::query($dataTable, $alias);
    }
}

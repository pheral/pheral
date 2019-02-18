<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\DB;

abstract class Model
{
    public function newQuery($dataTable = '', $alias = '', $connectionName = '')
    {
        return DB::connection($connectionName)->query($dataTable, $alias);
    }
}

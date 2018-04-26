<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Storage\Database\Query;

abstract class Model
{
    public function newQuery($dataTable = '', $alias = '')
    {
        return new Query($dataTable, $alias);
    }
}

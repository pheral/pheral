<?php

namespace Pheral\Essential\Layers;

use Pheral\Essential\Data\Base\DB;
use Pheral\Essential\Data\Base\Query;

abstract class Model
{
    public function newQuery($entity = '', $alias = ''): Query
    {
        return DB::query($entity, $alias);
    }
}

<?php

namespace Pheral\Essential\Data\Base\Result;

use Pheral\Essential\Data\Base\DB;

class InsertResult extends QueryResult
{
    public function lastInsertId()
    {
        return DB::connect()->lastInsertId();
    }
}

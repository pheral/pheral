<?php

namespace Pheral\Essential\Storage\Database\Result;

use Pheral\Essential\Storage\Database\DB;

class InsertResult extends QueryResult
{
    public function lastInsertId()
    {
        return DB::instance()->lastInsertId();
    }
}

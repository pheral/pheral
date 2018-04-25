<?php

namespace Pheral\Essential\Storage\DataBase\Result;

use Pheral\Essential\Storage\DataBase\DB;

class InsertResult extends QueryResult
{
    public function lastInsertId()
    {
        return DB::instance()->lastInsertId();
    }
}

<?php

namespace Pheral\Essential\Storage\Database\Result;

class InsertResult extends QueryResult
{
    public function lastInsertId()
    {
        return $this->query->getConnect()->getPdo()->lastInsertId();
    }
}

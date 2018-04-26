<?php

namespace Pheral\Essential\Storage\Database\Result;

use Pheral\Essential\Layers\DataTable;

class SelectResult extends QueryResult
{
    public function __construct($stmt, $table = null)
    {
        parent::__construct($stmt);
        if ($table && is_subclass_of($table, DataTable::class)) {
            $this->stmt->setFetchMode(\PDO::FETCH_CLASS, $table);
        }
    }

    public function all()
    {
        return $this->stmt->fetchAll();
    }

    public function row()
    {
        return $this->stmt->fetch();
    }
}

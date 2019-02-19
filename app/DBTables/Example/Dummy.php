<?php

namespace App\DBTables\Example;

use App\DBTables\Abstracts\DBTable;

class Dummy extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'test_id' => 'integer',
        'param' => 'string',
    ];
}

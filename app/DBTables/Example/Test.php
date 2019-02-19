<?php

namespace App\DBTables\Example;

use App\DBTables\Abstracts\DBTable;

class Test extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'title' => 'string',
    ];
}

<?php

namespace App\DataTables\Example;

use App\DataTables\Abstracts\DataTable;

class Dummy extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'test_id' => 'integer',
        'param' => 'string',
    ];
}

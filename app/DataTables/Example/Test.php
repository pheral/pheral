<?php

namespace App\DataTables\Example;

use App\DataTables\Abstracts\DataTable;

class Test extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'title' => 'string',
    ];
}

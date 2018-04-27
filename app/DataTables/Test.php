<?php

namespace App\DataTables;

use App\DataTables\Abstracts\DataTable;

class Test extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'title' => 'string',
    ];
}

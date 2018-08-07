<?php

namespace App\DataTables\Reports;

use App\DataTables\Abstracts\DataTable;

class Tasks extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'is_todo' => 'bool',
        'is_work' => 'bool',
        'is_hold' => 'bool',
        'is_done' => 'bool',
        'start_at' => 'datetime',
        'stop_at' => 'datetime',
    ];
}

<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;

class Activities extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'slug' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];
}

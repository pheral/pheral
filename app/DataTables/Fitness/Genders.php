<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;

class Genders extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'slug' => 'string',
        'title' => 'string',
    ];
}

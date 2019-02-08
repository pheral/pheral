<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;

class UserExercise extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'user_id' => 'integer',
        'exercise_id' => 'integer',
        'title' => 'string',
        'is_favorite' => 'bool',
        'position' => 'integer',
    ];
}

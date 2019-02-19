<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class ExerciseGoals extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'slug' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];

    public static function relations()
    {
        return [
            // targets:
            'values' => Relations::hasMany(Exercises::class)->setKeys('goal_id'),
        ];
    }
}

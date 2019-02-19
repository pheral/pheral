<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class ExerciseMusclePriority extends DBTable
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
            // pivots:
            'exerciseMuscles' => Relations::hasMany(ExerciseMuscle::class)->setKeys('priority_id'),
        ];
    }
}

<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class ExerciseMuscle extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'exercise_id' => 'integer',
        'muscle_id' => 'integer',
        'priority_id' => 'integer',
    ];

    public static function relations()
    {
        return [
            // targets:
            'exercise' => Relations::belongsTo(Exercises::class)->setKeys('exercise_id'),
            'priority' => Relations::belongsTo(ExerciseMusclePriority::class)->setKeys('priority_id'),
            'muscle' => Relations::belongsTo(Muscles::class)->setKeys('muscle_id'),
        ];
    }
}

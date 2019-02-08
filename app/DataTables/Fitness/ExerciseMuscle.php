<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class ExerciseMuscle extends DataTable
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

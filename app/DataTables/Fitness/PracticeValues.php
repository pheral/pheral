<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class PracticeValues extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'practice_id' => 'integer',
        'workout_exercise_id' => 'integer',
        'unit_id' => 'integer',
        'value' => 'integer',
        'attempt' => 'integer',
    ];

    public static function relations()
    {
        return [
            // targets:
            'practice' => Relations::belongsTo(Practices::class)
                ->setKeys('practice_id'),
            'exercise' => Relations::belongsToThrough(Exercises::class, WorkoutExercise::class)
                ->setKeys('workout_exercise_id', 'exercise_id'),
            'unit' => Relations::belongsTo(Units::class)
                ->setKeys('unit_id'),
            // pivots:
            'workoutExercise' => Relations::belongsTo(WorkoutExercise::class)
                ->setKeys('workout_exercise_id'),
        ];
    }
}

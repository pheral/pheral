<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class WorkoutExercise extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'workout_id' => 'integer',
        'step_id' => 'integer',
        'exercise_id' => 'integer',
        'position' => 'integer',
    ];

    public static function relations()
    {
        return [
            // targets:
            'workout' => Relations::belongsTo(Workouts::class)
                ->setKeys('workout_id'),
            'step' => Relations::belongsTo(WorkoutSteps::class)
                ->setKeys('step_id'),
            'exercise' => Relations::belongsTo(Exercises::class)
                ->setKeys('exercise_id'),
            'values' => Relations::hasMany(PracticeValues::class)
                ->setKeys('workout_exercise_id'),
        ];
    }
}

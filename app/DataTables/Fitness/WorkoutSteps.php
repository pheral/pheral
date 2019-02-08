<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class WorkoutSteps extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'workout_id' => 'integer',
        'title' => 'string',
        'position' => 'integer',
    ];

    public static function relations()
    {
        return [
            // targets:
            'workout' => Relations::belongsTo(Workouts::class)
                ->setKeys('workout_id'),
            'exercises' => Relations::hasManyMembers(Exercises::class, WorkoutExercise::class)
                ->setKeys('step_id', 'exercise_id'),
            // pivots:
            'workoutExercises' => Relations::hasMany(WorkoutExercise::class)
                ->setKeys('step_id'),
        ];
    }
}

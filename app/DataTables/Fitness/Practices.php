<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class Practices extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'user_id' => 'integer',
        'workout_id' => 'integer',
        'status_id' => 'integer',
        'activity_id' => 'integer',
        'activity_hit' => 'integer',
        'description' => 'string',
        'start_at' => 'datetime',
        'stop_at' => 'datetime',
    ];

    public static function relations()
    {
        return [
            // targets:
            'user' => Relations::belongsTo(Users::class)
                ->setKeys('user_id'),
            'status' => Relations::belongsTo(PracticeStatuses::class)
                ->setKeys('status_id'),
            'activity' => Relations::belongsTo(Activities::class)
                ->setKeys('activity_id'),
            'workout' => Relations::belongsTo(Workouts::class)
                ->setKeys('workout_id'),
            'values' => Relations::hasMany(PracticeValues::class)
                ->setKeys('practice_id'),
            'steps' => Relations::belongsToNeighbors(WorkoutSteps::class, Workouts::class)
                ->setKeys('workout_id', 'workout_id'),
            // pivots:
            'workoutExercises' => Relations::belongsToNeighbors(WorkoutExercise::class, Workouts::class)
                ->setKeys('workout_id', 'workout_id'),
        ];
    }
}

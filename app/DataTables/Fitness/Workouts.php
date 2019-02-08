<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class Workouts extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'user_id' => 'integer',
        'activity_id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'position' => 'integer',
    ];

    public static function relations()
    {
        return [
            // targets:
            'user' => Relations::belongsTo(Users::class)->setKeys('user_id'),
            'activity' => Relations::belongsTo(Activities::class)->setKeys('activity_id'),
            'practices' => Relations::hasMany(Practices::class)->setKeys('workout_id'),
            'steps' => Relations::hasMany(WorkoutSteps::class)->setKeys('workout_id'),
            // pivots:
            'workoutExercises' => Relations::hasMany(WorkoutExercise::class)->setKeys('workout_id'),
        ];
    }
}

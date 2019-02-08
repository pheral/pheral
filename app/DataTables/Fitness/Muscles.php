<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class Muscles extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'group_id' => 'integer',
        'slug' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];

    public static function relations()
    {
        return [
            // targets:
            'group' => Relations::belongsTo(MuscleGroups::class)->setKeys('group_id'),
            'priority' => Relations::hasManyMembers(ExerciseMusclePriority::class, ExerciseMuscle::class)
                ->setKeys('muscle_id', 'priority_id'),
            'exercises' => Relations::hasManyMembers(Exercises::class, ExerciseMuscle::class)
                ->setKeys('muscle_id', 'exercise_id'),
            // pivots:
            'exerciseMuscles' => Relations::hasMany(ExerciseMuscle::class)->setKeys('muscle_id'),
        ];
    }
}

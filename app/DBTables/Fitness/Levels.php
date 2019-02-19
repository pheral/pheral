<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class Levels extends DBTable
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
            'user' => Relations::hasMany(Users::class)
                ->setKeys('level_id'),
            'exercises' => Relations::hasManyMembers(Exercises::class, UserExercise::class)
                ->setKeys('level_id', 'exercise_id'),
            // pivot:
            'userExercises' => Relations::hasMany(UserExercise::class)
                ->setKeys('level_id'),
        ];
    }
}

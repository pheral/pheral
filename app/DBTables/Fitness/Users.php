<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

/**
 * Class Users
 * @property integer $id
 */
class Users extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'gender_id' => 'integer',
        'level_id' => 'integer',
        'progress' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'pass' => 'string',
    ];

    public static function relations()
    {
        return [
            // targets:
            'gender' => Relations::belongsTo(Genders::class)
                ->setKeys('gender_id'),
            'level' => Relations::belongsTo(Levels::class)
                ->setKeys('level_id'),
            'data' => Relations::hasMany(UserData::class)
                ->setKeys('user_id'),
            'practices' => Relations::hasMany(Practices::class)
                ->setKeys('user_id'),
            'workouts' => Relations::hasMany(Workouts::class)
                ->setKeys('user_id'),
            'options' => Relations::hasManyMembers(UserDataOptions::class, UserData::class)
                ->setKeys('user_id', 'option_id'),
            'exercises' => Relations::hasManyMembers(Exercises::class, UserExercise::class)
                ->setKeys('user_id', 'exercise_id'),
            // pivots:
            'userExercises' => Relations::hasMany(UserExercise::class)
                ->setKeys('user_id'),
        ];
    }
}

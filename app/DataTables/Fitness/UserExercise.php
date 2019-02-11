<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class UserExercise extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'user_id' => 'integer',
        'exercise_id' => 'integer',
        'level_id' => 'integer',
        'title' => 'string',
        'is_favorite' => 'bool',
        'progress' => 'integer',
        'position' => 'integer',
    ];

    public static function relations()
    {
        return [
            // targets:
            'user' => Relations::belongsTo(Users::class)->setKeys('user_id'),
            'level' => Relations::belongsTo(Levels::class)->setKeys('level_id'),
            'exercise' => Relations::belongsTo(Exercises::class)->setKeys('exercise_id'),
        ];
    }
}

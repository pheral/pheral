<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class Levels extends DataTable
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
            'user' => Relations::hasMany(Users::class)->setKeys('level_id'),
            // pivot:
            'exerciseLevels' => Relations::hasMany(ExerciseLevel::class)->setKeys('level_id'),
        ];
    }
}

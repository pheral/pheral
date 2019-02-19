<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class Units extends DBTable
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
            'exerciseUnits' => Relations::hasMany(ExerciseUnit::class)->setKeys('unit_id'),
        ];
    }
}

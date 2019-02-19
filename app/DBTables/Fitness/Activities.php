<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class Activities extends DBTable
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
            'workouts' => Relations::hasMany(Workouts::class)
                ->setKeys('activity_id'),
            'practices' => Relations::hasMany(Practices::class)
                ->setKeys('activity_id'),
        ];
    }
}

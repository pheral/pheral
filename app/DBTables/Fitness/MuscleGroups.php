<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class MuscleGroups extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'parent_id' => 'integer',
        'slug' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];

    public static function relations()
    {
        return [
            // targets:
            'group' => Relations::hasMany(Muscles::class)->setKeys('group_id'),
        ];
    }
}

<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class MuscleGroups extends DataTable
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

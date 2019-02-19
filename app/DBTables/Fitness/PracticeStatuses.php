<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class PracticeStatuses extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'slug' => 'string',
        'title' => 'string',
    ];

    public static function relations()
    {
        return [
            // targets:
            'practices' => Relations::hasMany(Practices::class)->setKeys('practice_id'),
        ];
    }
}

<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class PracticeStatuses extends DataTable
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

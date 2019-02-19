<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class Genders extends DBTable
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
            'users' => Relations::hasMany(Users::class)
                ->setKeys('gender_id'),
        ];
    }
}

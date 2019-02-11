<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class Genders extends DataTable
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

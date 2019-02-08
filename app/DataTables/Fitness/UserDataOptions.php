<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class UserDataOptions extends DataTable
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
            'option' => Relations::hasMany(UserData::class)->setKeys('option_id'),
        ];
    }
}

<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class UserData extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'user_id' => 'integer',
        'option_id' => 'integer',
        'value' => 'string',
        'created_at' => 'datetime',
    ];

    public static function relations()
    {
        return [
            // targets:
            'user' => Relations::belongsTo(Users::class)->setKeys('user_id'),
            'option' => Relations::belongsTo(UserDataOptions::class)->setKeys('option_id'),
        ];
    }
}

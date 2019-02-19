<?php

namespace App\DBTables\Fitness;

use App\DBTables\Abstracts\DBTable;
use Pheral\Essential\Storage\Database\Relations;

class ExerciseUnit extends DBTable
{
    protected static $scheme = [
        'id' => 'integer',
        'exercise_id' => 'integer',
        'unit_id' => 'integer',
    ];

    public static function relations()
    {
        return [
            // targets:
            'unit' => Relations::belongsTo(Units::class)->setKeys('unit_id'),
        ];
    }
}

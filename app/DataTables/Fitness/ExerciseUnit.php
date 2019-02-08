<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class ExerciseUnit extends DataTable
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

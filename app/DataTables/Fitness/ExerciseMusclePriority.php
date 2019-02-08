<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class ExerciseMusclePriority extends DataTable
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
            // pivots:
            'exerciseMuscles' => Relations::hasMany(ExerciseMuscle::class)->setKeys('priority_id'),
        ];
    }
}

<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class PracticeValues extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'practice_id' => 'integer',
        'exercise_id' => 'integer',
        'step_id' => 'integer',
        'value' => 'integer',
        'attempt' => 'integer',
    ];

    public static function relations()
    {
        return [
            // targets:
            'practice' => Relations::belongsTo(Practices::class)->setKeys('practice_id'),
            'exercise' => Relations::belongsTo(Exercises::class)->setKeys('exercise_id'),
            'step' => Relations::belongsTo(WorkoutSteps::class)->setKeys('step_id'),
        ];
    }
}

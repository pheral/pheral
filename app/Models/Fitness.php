<?php

namespace App\Models;

use App\DataTables\Fitness\Practices;
use App\DataTables\Fitness\Users;
use App\Models\Abstracts\Model;
use Pheral\Essential\Storage\Database\DB;

class Fitness extends Model
{
    public function __construct()
    {
        DB::setPrefix('fitness');
    }

    public function getPractices()
    {
        $user = Users::query()
            ->where('email', '=', 'test@pheral.vhost')
            ->select()
            ->row();

        $practices = Practices::query()
            ->where('user_id', '=', $user->id)
            ->with([
                    'user',
                    'status',
                    'activity',
                    'workout' => [
                        'activity',
                        'steps' => [
                            'workoutExercises' => [
                                'values' => 'unit',
                                'exercise' => [
                                    'goal',
                                    'units',
                                    'exerciseMuscles' => [
                                        'priority',
                                        'muscle' => 'group',
                                    ],
                                ],
                            ],
                        ],
                    ],
            ])
            ->select()
            ->all();

//        debug($practices, DB::history());

        return $practices;
    }
}

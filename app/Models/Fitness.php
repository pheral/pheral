<?php

namespace App\Models;

use App\DataTables\Fitness\Practices;
use App\DataTables\Fitness\PracticeValues;
use App\DataTables\Fitness\Users;
use App\Models\Abstracts\Model;
use Pheral\Essential\Storage\Database\DB;
use Pheral\Essential\Storage\Database\Query;

class Fitness extends Model
{
    public function __construct()
    {
        DB::setPrefix('fitness');
    }

    public function getUser($email = null)
    {
        return Users::query()
            ->with([
                'gender',
                'level',
                'data' => 'option'
            ])
            ->where('email', '=', $email ?? 'test@pheral.vhost')
            ->select()
            ->row();
    }

    public function getPractices(Users $user)
    {
        $practices = Practices::query('p')
            ->where('user_id', '=', $user->id)
            ->with([
                    'status',
                    'activity',
                    'workout' => [
                        'activity',
                        'steps' => [
                            'workoutExercises' => [
                                'values',
                                'exercise' => [
                                    'goal',
                                    'units',
                                    'exerciseMuscles' => [
                                        'priority',
                                        'muscle' => 'group',
                                    ],
                                    'levels' => function (Query $query) use ($user) {
                                        $query->where('pivot.user_id', '=', $user->id);
                                    },
                                ],
                            ],
                        ],
                    ],
            ])
            ->select()
            ->all();

        foreach ($practices as $practice) {
            $practice->user = $user;
            $practice->maxAttempts = 0;
            foreach ($practice->workout->steps as $step) {
                $stepRows = 0;
                foreach ($step->workoutExercises as $workoutExercise) {
                    $exerciseRows = 0;
                    $valuesByUnits = data_group($workoutExercise->values, 'unit_id');
                    $maxAttempts = max(data_pluck($workoutExercise->values, 'attempt'));
                    $practice->maxAttempts = max($practice->maxAttempts, $maxAttempts);
                    foreach ($workoutExercise->exercise->units as $exerciseUnit) {
                        $exerciseRows +=1;
                        $exerciseUnit->values = array_get($valuesByUnits, $exerciseUnit->id);
                    }
                    if ($exerciseRows > 1) {
                        $workoutExercise->exercise->rowspan = $exerciseRows;
                    }
                    $stepRows += $exerciseRows;
                }
                if ($stepRows > 1) {
                    $step->rowspan = $stepRows;
                }
            };
        }

//        debug($practices, DB::history());

        return $practices;
    }
}

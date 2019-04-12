<?php

namespace App\Models\Fitness;

use App\DBTables\Fitness\Practices;
use App\DBTables\Fitness\UserExercise;
use App\DBTables\Fitness\Users;
use App\DBTables\Fitness\Workouts;
use App\Models\Abstracts\Model;
use Pheral\Essential\Storage\Database\Query;

class Home extends Model
{
    public function getWorkouts(Users $user)
    {
        $workouts = Workouts::query('w')
            ->where('user_id', '=', $user->id)
            ->select()
            ->all();
        return $workouts;
    }

    public function getPracticeExercises(Users $user)
    {
        $userExercises = UserExercise::query('ue')
            ->where('user_id', '=', $user->id)
            ->with([
                'exercise' => [
                    'goal',
                    'units',
                ],
            ])
            ->select()
            ->all();

        $exerciseIds = [];
        foreach ($userExercises as $userExercise) {
            $exerciseIds[] = $userExercise->exercise;
        }


        $workouts = Workouts::query('w')
            ->where('user_id', '=', $user->id)
            ->select()
            ->all();
        return $workouts;
    }

    public function getPractices(Users $user)
    {
        $practices = Practices::query('p')
            ->where('user_id', '=', $user->id)
            ->with([
                'status',
                'activity',
                'workout' => [
                    'steps' => function (Query $query) {
                        $query
                            ->orderBy('target.position', 'ASC')
                            ->with([
                                'workoutExercises' => [
                                    'values',
                                    'exercise' => [
                                        'goal',
                                        'units',
                                    ],
                                ],
                            ]);
                    },
                ],
            ])
            ->select()
            ->all();
        // profiler()->database()->debug();
        foreach ($practices as $practice) {
            $practice->user = $user;
            $practice->maxAttempts = 1;
            foreach ($practice->workout->steps as $step) {
                $stepRows = 0;
                foreach ($step->workoutExercises as $indexExercise => $workoutExercise) {
                    $exercise = $workoutExercise->exercise;
                    $exercise->isFirstInStep = !$indexExercise;
                    $exerciseRows = 0;
                    $valuesByUnits = data_group($workoutExercise->values, 'unit_id');
                    $maxAttempts = max(data_pluck($workoutExercise->values, 'attempt'));
                    $practice->maxAttempts = max($practice->maxAttempts, $maxAttempts);
                    foreach ($exercise->units as $indexUnit => $unit) {
                        $exerciseRows += 1;
                        $unitValues = array_get($valuesByUnits, $unit->id);
                        $unit->values = data_pluck($unitValues, 'value', 'attempt');
                        $unit->isFirstInExercise = !$indexUnit;
                    }
                    if ($exerciseRows > 1) {
                        $exercise->rowspan = $exerciseRows;
                    }
                    $stepRows += $exerciseRows;
                }
                if ($stepRows > 1) {
                    $step->rowspan = $stepRows;
                }
            };
        }
        return $practices;
    }
}

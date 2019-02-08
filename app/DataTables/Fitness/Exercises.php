<?php

namespace App\DataTables\Fitness;

use App\DataTables\Abstracts\DataTable;
use Pheral\Essential\Storage\Database\Relations;

class Exercises extends DataTable
{
    protected static $scheme = [
        'id' => 'integer',
        'goal_id' => 'integer',
        'slug' => 'string',
        'title' => 'string',
        'description' => 'string',
    ];

    public static function relations()
    {
        return [
            // targets:
            'goal' => Relations::belongsTo(ExerciseGoals::class)
                ->setKeys('goal_id'),
            'values' => Relations::hasMany(PracticeValues::class)
                ->setKeys('exercise_id'),
            'users' => Relations::hasManyMembers(Users::class, UserExercise::class)
                ->setKeys('exercise_id', 'user_id'),
            'levels' => Relations::hasManyMembers(Levels::class, ExerciseLevel::class)
                ->setKeys('exercise_id', 'level_id'),
            'units' => Relations::hasManyMembers(Units::class, ExerciseUnit::class)
                ->setKeys('exercise_id', 'unit_id'),
            'muscles' => Relations::hasManyMembers(Muscles::class, ExerciseMuscle::class)
                ->setKeys('exercise_id', 'muscle_id'),
            'musclePriority' => Relations::hasManyMembers(ExerciseMusclePriority::class, ExerciseMuscle::class)
                ->setKeys('exercise_id', 'priority_id'),
            'workouts' => Relations::hasManyMembers(Workouts::class, WorkoutExercise::class)
                ->setKeys('exercise_id', 'workout_id'),
            'workoutSteps' => Relations::hasManyMembers(WorkoutSteps::class, WorkoutExercise::class)
                ->setKeys('exercise_id', 'step_id'),

            // pivots:
            'userExercises' => Relations::hasMany(UserExercise::class)
                ->setKeys('exercise_id'),
            'exerciseLevels' => Relations::hasMany(ExerciseLevel::class)
                ->setKeys('exercise_id'),
            'exerciseUnits' => Relations::hasMany(ExerciseUnit::class)
                ->setKeys('exercise_id'),
            'exerciseMuscles' => Relations::hasMany(ExerciseMuscle::class)
                ->setKeys('exercise_id'),
            'workoutExercises' => Relations::hasMany(WorkoutExercise::class)
                ->setKeys('exercise_id'),
        ];
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkoutRoutine;

class WorkoutRoutinePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkoutRoutine $workoutRoutine): bool
    {
        return $user->id === $workoutRoutine->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkoutRoutine $workoutRoutine): bool
    {
        return $user->id === $workoutRoutine->user_id;
    }
}

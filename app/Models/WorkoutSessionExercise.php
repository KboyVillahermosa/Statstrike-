<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutSessionExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_session_id',
        'exercise_id',
        'order',
        'sets_completed',
        'reps_completed',
        'duration_seconds',
        'set_data',
        'notes',
    ];

    protected $casts = [
        'order' => 'integer',
        'sets_completed' => 'integer',
        'reps_completed' => 'integer',
        'duration_seconds' => 'integer',
        'set_data' => 'array',
    ];

    /**
     * Get the workout session that owns this exercise.
     */
    public function workoutSession(): BelongsTo
    {
        return $this->belongsTo(WorkoutSession::class);
    }

    /**
     * Get the exercise.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}

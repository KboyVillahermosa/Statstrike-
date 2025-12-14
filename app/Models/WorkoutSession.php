<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workout_program_id',
        'name',
        'started_at',
        'completed_at',
        'duration_seconds',
        'total_exercises',
        'total_sets',
        'total_reps',
        'rpe',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'duration_seconds' => 'integer',
        'total_exercises' => 'integer',
        'total_sets' => 'integer',
        'total_reps' => 'integer',
        'rpe' => 'integer',
    ];

    /**
     * Get the user that owns the workout session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the workout program for this session.
     */
    public function workoutProgram(): BelongsTo
    {
        return $this->belongsTo(WorkoutProgram::class);
    }

    /**
     * Get the exercises for this workout session.
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(WorkoutSessionExercise::class)->orderBy('order');
    }
}

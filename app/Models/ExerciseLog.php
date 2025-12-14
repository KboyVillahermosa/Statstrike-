<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExerciseLog extends Model
{
    protected $fillable = [
        'user_id',
        'routine_id',
        'program_id',
        'exercise_title',
        'exercise_key',
        'reps',
        'duration_seconds',
        'tracked',
        'accuracy',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'tracked' => 'boolean',
    ];
}

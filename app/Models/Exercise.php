<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'instructions',
        'target_muscles',
        'difficulty',
        'demo_video_url',
        'image_url',
        'duration_seconds',
        'is_active',
    ];

    protected $casts = [
        'target_muscles' => 'array',
        'duration_seconds' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the exercise.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ExerciseCategory::class, 'category_id');
    }

    /**
     * Get the workout programs that include this exercise.
     */
    public function workoutPrograms(): BelongsToMany
    {
        return $this->belongsToMany(WorkoutProgram::class, 'workout_program_exercises')
            ->withPivot('order', 'sets', 'reps', 'duration_seconds', 'rest_seconds', 'notes')
            ->withTimestamps();
    }
}

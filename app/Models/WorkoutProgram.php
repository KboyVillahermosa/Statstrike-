<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutProgram extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'level',
        'goal',
        'duration_minutes',
        'days_per_week',
        'is_custom',
        'created_by',
        'is_active',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'days_per_week' => 'integer',
        'is_custom' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user who created this program.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the exercises for this workout program.
     */
    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'workout_program_exercises')
            ->withPivot('order', 'sets', 'reps', 'duration_seconds', 'rest_seconds', 'notes')
            ->orderByPivot('order')
            ->withTimestamps();
    }

    /**
     * Get the workout sessions for this program.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(WorkoutSession::class);
    }
}

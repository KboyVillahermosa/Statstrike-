<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutRoutineDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_routine_id',
        'day_of_week',
        'title',
        'description',
        'exercises',
        'rounds',
        'intensity',
        'rest_minutes',
        'tags',
    ];

    protected $casts = [
        'exercises' => 'array',
        'tags' => 'array',
        'rounds' => 'integer',
        'rest_minutes' => 'integer',
    ];

    /**
     * Get the routine that owns this day.
     */
    public function routine(): BelongsTo
    {
        return $this->belongsTo(WorkoutRoutine::class, 'workout_routine_id');
    }
}

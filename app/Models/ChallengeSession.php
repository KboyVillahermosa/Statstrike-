<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'challenge_id',
        'user_id',
        'duration_seconds',
        'count',
        'movement_data',
        'rpe',
        'notes',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'duration_seconds' => 'integer',
        'count' => 'integer',
        'rpe' => 'integer',
        'movement_data' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the challenge that owns this session.
     */
    public function challenge(): BelongsTo
    {
        return $this->belongsTo(Challenge::class);
    }

    /**
     * Get the user that owns this session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

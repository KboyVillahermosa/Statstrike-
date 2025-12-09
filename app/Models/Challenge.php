<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'target_count',
        'target_unit',
        'duration_days',
        'is_active',
        'starts_at',
        'ends_at',
        'image_url',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'target_count' => 'integer',
        'duration_days' => 'integer',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    /**
     * Get the users that joined this challenge.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot('progress', 'joined_at', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Get the sessions for this challenge.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(ChallengeSession::class);
    }

    /**
     * Get the user's progress for this challenge.
     */
    public function getUserProgress($userId): int
    {
        $pivot = $this->users()->where('user_id', $userId)->first();
        return $pivot ? (int) $pivot->pivot->progress : 0;
    }

    /**
     * Get the progress percentage.
     */
    public function getProgressPercentage($userId): float
    {
        if ($this->target_count <= 0) {
            return 0;
        }
        
        $progress = $this->getUserProgress($userId);
        return min(100, round(($progress / $this->target_count) * 100, 2));
    }
}

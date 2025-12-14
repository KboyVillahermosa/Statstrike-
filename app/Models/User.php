<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
        'fitness_goal',
        'experience_level',
        'subscription_tier',
        'device_capability',
        'mediapipe_supported',
        'points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'device_capability' => 'array',
            'mediapipe_supported' => 'boolean',
        ];
    }

    /**
     * Get the workouts for the user.
     */
    public function workouts()
    {
        return $this->hasMany(Workout::class);
    }

    /**
     * Get the challenges that the user has joined.
     */
    public function challenges()
    {
        return $this->belongsToMany(Challenge::class)
            ->withPivot('progress', 'joined_at', 'completed_at')
            ->withTimestamps();
    }

    /**
     * Get the challenge sessions for the user.
     */
    public function challengeSessions()
    {
        return $this->hasMany(ChallengeSession::class);
    }

    /**
     * Get the posts created by the user.
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the likes given by the user.
     */
    public function postLikes()
    {
        return $this->hasMany(PostLike::class);
    }

    /**
     * Get the comments created by the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the workout sessions for the user.
     */
    public function workoutSessions()
    {
        return $this->hasMany(WorkoutSession::class);
    }
}

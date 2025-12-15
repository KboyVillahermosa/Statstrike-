<?php

namespace App\Http\Controllers\Api;

use App\Models\Challenge;
use App\Models\ChallengeSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChallengeController extends BaseController
{
    /**
     * List active challenges.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $challenges = Challenge::where('is_active', true)
            ->withCount('users')
            ->get()
            ->map(function ($challenge) use ($user) {
                $userChallenge = $challenge->users()->where('user_id', $user->id)->first();
                
                return [
                    'id' => $challenge->id,
                    'title' => $challenge->title,
                    'description' => $challenge->description,
                    'type' => $challenge->type,
                    'target_count' => $challenge->target_count,
                    'target_unit' => $challenge->target_unit,
                    'duration_days' => $challenge->duration_days,
                    'participants_count' => $challenge->users_count,
                    'progress' => $userChallenge ? (int) $userChallenge->pivot->progress : 0,
                    'progress_percentage' => $challenge->getProgressPercentage($user->id),
                    'joined' => $userChallenge !== null,
                    'completed' => $userChallenge && $userChallenge->pivot->completed_at !== null,
                ];
            });

        return $this->success($challenges);
    }

    /**
     * Join a challenge.
     */
    public function join(Request $request, Challenge $challenge): JsonResponse
    {
        $user = $request->user();

        // Check if already joined
        if (!$challenge->users()->where('user_id', $user->id)->exists()) {
            $challenge->users()->attach($user->id, [
                'progress' => 0,
                'joined_at' => now(),
            ]);
        }

        return $this->success(null, 'Successfully joined the challenge!');
    }

    /**
     * Get challenge session data.
     */
    public function startSession(Request $request, Challenge $challenge): JsonResponse
    {
        $user = $request->user();
        
        // Check if user has joined the challenge
        $userChallenge = $challenge->users()->where('user_id', $user->id)->first();
        
        if (!$userChallenge) {
            // Auto-join the challenge if not joined
            $challenge->users()->attach($user->id, [
                'progress' => 0,
                'joined_at' => now(),
            ]);
        }

        return $this->success([
            'id' => $challenge->id,
            'title' => $challenge->title,
            'description' => $challenge->description,
            'type' => $challenge->type,
            'target_count' => $challenge->target_count,
            'target_unit' => $challenge->target_unit,
        ]);
    }

    /**
     * Store a completed challenge session.
     */
    public function storeSession(Request $request, Challenge $challenge): JsonResponse
    {
        $validated = $request->validate([
            'duration_seconds' => ['required', 'integer', 'min:1'],
            'count' => ['required', 'integer', 'min:0'],
            'rpe' => ['nullable', 'integer', 'between:1,10'],
            'movement_data' => ['nullable', 'array'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        // Create session
        $session = ChallengeSession::create([
            'challenge_id' => $challenge->id,
            'user_id' => $user->id,
            'duration_seconds' => $validated['duration_seconds'],
            'count' => $validated['count'],
            'rpe' => $validated['rpe'] ?? null,
            'movement_data' => $validated['movement_data'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'started_at' => now()->subSeconds($validated['duration_seconds']),
            'completed_at' => now(),
        ]);

        // Update user progress in challenge
        $userChallenge = $challenge->users()->where('user_id', $user->id)->first();
        $newProgress = 0;
        $isCompleted = false;
        
        if ($userChallenge) {
            $newProgress = (int) $userChallenge->pivot->progress + $validated['count'];
            $challenge->users()->updateExistingPivot($user->id, [
                'progress' => $newProgress,
            ]);

            // Check if challenge is completed
            if ($newProgress >= $challenge->target_count && !$userChallenge->pivot->completed_at) {
                $challenge->users()->updateExistingPivot($user->id, [
                    'completed_at' => now(),
                ]);
                $isCompleted = true;
            }
        }

        $message = "Workout completed! Added {$validated['count']} {$challenge->target_unit} to your progress.";
        if ($isCompleted) {
            $message = "🎉 Challenge completed! You've reached {$challenge->target_count} {$challenge->target_unit}!";
        } else {
            $message .= " Progress: {$newProgress} / {$challenge->target_count} {$challenge->target_unit}";
        }

        return $this->success([
            'session' => $session,
            'progress' => $newProgress,
            'target' => $challenge->target_count,
            'completed' => $isCompleted,
        ], $message);
    }
}

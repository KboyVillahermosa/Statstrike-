<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeSession;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChallengeController extends Controller
{
    /**
     * Display a listing of active challenges.
     */
    public function index(Request $request): Response
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

        return Inertia::render('Challenges', [
            'challenges' => $challenges,
        ]);
    }

    /**
     * Start a workout session for a challenge.
     */
    public function startSession(Request $request, Challenge $challenge): Response
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

        return Inertia::render('Workouts/ChallengeSession', [
            'challenge' => [
                'id' => $challenge->id,
                'title' => $challenge->title,
                'description' => $challenge->description,
                'type' => $challenge->type,
                'target_count' => $challenge->target_count,
                'target_unit' => $challenge->target_unit,
            ],
        ]);
    }

    /**
     * Store a completed workout session.
     */
    public function storeSession(Request $request, Challenge $challenge): RedirectResponse
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

        // Create success message with progress details
        $progressMessage = "Workout completed! Added {$validated['count']} {$challenge->target_unit} to your progress.";
        if ($isCompleted) {
            $progressMessage = "🎉 Challenge completed! You've reached {$challenge->target_count} {$challenge->target_unit}!";
        } else {
            $progressMessage .= " Progress: {$newProgress} / {$challenge->target_count} {$challenge->target_unit}";
        }

        // Use Inertia redirect to ensure flash messages are properly passed
        return redirect()
            ->route('challenges.index')
            ->with('success', $progressMessage);
    }

    /**
     * Join a challenge.
     */
    public function join(Request $request, Challenge $challenge): RedirectResponse
    {
        $user = $request->user();

        // Check if already joined
        if (!$challenge->users()->where('user_id', $user->id)->exists()) {
            $challenge->users()->attach($user->id, [
                'progress' => 0,
                'joined_at' => now(),
            ]);
        }

        return redirect()
            ->route('challenges.index')
            ->with('success', 'Successfully joined the challenge!');
    }
}

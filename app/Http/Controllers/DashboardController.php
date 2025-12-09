<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with real data.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        // Calculate total workouts
        $totalWorkouts = $user->workouts()->count();
        $lastMonthWorkouts = $user->workouts()
            ->where('created_at', '>=', now()->subMonth())
            ->count();
        $workoutsChange = $totalWorkouts > 0 
            ? $lastMonthWorkouts 
            : 0;

        // Calculate total time trained
        $totalMinutes = $user->workouts()->sum('duration');
        $totalHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;
        $timeTrained = $totalHours . 'h ' . $remainingMinutes . 'm';
        
        $lastMonthMinutes = $user->workouts()
            ->where('created_at', '>=', now()->subMonth())
            ->sum('duration');
        $lastMonthHours = floor($lastMonthMinutes / 60);
        $timeChange = $lastMonthHours > 0 
            ? '+' . $lastMonthHours . 'h this month'
            : 'No change';

        // Weekly activity data (last 7 days)
        $weeklyActivity = [];
        $daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        
        // Get max workouts in a single day for scaling
        $maxWorkoutsInWeek = 0;
        $workoutsByDay = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $workoutsCount = $user->workouts()
                ->whereDate('created_at', $date->toDateString())
                ->count();
            
            $workoutsByDay[$i] = $workoutsCount;
            $maxWorkoutsInWeek = max($maxWorkoutsInWeek, $workoutsCount);
        }
        
        // Build weekly activity array with proper scaling
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $daysOfWeek[$date->dayOfWeek];
            $workoutsCount = $workoutsByDay[$i];
            
            // Calculate height percentage (max 100% for visual representation)
            // Scale based on max workouts in the week, with a minimum of 10% if there are workouts
            $height = 0;
            if ($maxWorkoutsInWeek > 0) {
                $height = min(100, ($workoutsCount / max($maxWorkoutsInWeek, 1)) * 100);
            }
            
            // Minimum 10% height if there are workouts, 5% if no workouts
            $height = $workoutsCount > 0 ? max(10, $height) : 5;
            
            $weeklyActivity[] = [
                'label' => $dayName,
                'height' => $height,
            ];
        }

        // Get active challenges for the user
        $userChallenges = $user->challenges()
            ->where('is_active', true)
            ->withPivot('progress', 'joined_at', 'completed_at')
            ->get()
            ->map(function ($challenge) use ($user) {
                $progress = (int) ($challenge->pivot->progress ?? 0);
                return [
                    'id' => $challenge->id,
                    'title' => $challenge->title,
                    'current' => $progress,
                    'total' => $challenge->target_count,
                ];
            })
            ->take(2); // Limit to 2 for display

        // Get recent workouts (last 5)
        $recentWorkouts = $user->workouts()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($workout) {
                // Extract workout type from drills (first few words)
                $drills = $workout->drills ?? 'Workout';
                $type = explode(' ', $drills)[0] ?? 'Workout';
                if (strlen($type) > 20) {
                    $type = substr($type, 0, 20) . '...';
                }

                // Format duration
                $duration = $workout->duration . ' min';

                // Format RPE
                $rpe = $workout->rpe . '/10';

                // Format date
                $date = $workout->created_at->diffForHumans();

                return [
                    'id' => $workout->id,
                    'type' => $type,
                    'duration' => $duration,
                    'rpe' => $rpe,
                    'date' => $date,
                ];
            });

        // Calculate followers (placeholder - you can implement this later)
        $followers = 0; // TODO: Implement followers system
        $followersChange = '+0 this month';

        // Calculate leaderboard rank (placeholder - you can implement this later)
        $leaderboardRank = '#0'; // TODO: Implement leaderboard system
        $rankChange = 'Top 0%';

        return Inertia::render('Dashboard', [
            'stats' => [
                'totalWorkouts' => [
                    'metric' => (string) $totalWorkouts,
                    'change' => $workoutsChange > 0 ? '+' . $workoutsChange . ' since last month' : 'No workouts yet',
                    'changePositive' => $workoutsChange > 0,
                ],
                'timeTrained' => [
                    'metric' => $timeTrained,
                    'change' => $timeChange,
                    'changePositive' => $lastMonthHours > 0,
                ],
                'followers' => [
                    'metric' => (string) $followers,
                    'change' => $followersChange,
                    'changePositive' => true,
                ],
                'leaderboardRank' => [
                    'metric' => $leaderboardRank,
                    'change' => $rankChange,
                    'changePositive' => true,
                ],
            ],
            'weeklyActivity' => $weeklyActivity,
            'activeChallenges' => $userChallenges,
            'recentWorkouts' => $recentWorkouts,
        ]);
    }
}


<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    /**
     * Get dashboard data.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Calculate total workouts
        $totalWorkouts = $user->workouts()->count();
        $lastMonthWorkouts = $user->workouts()
            ->where('created_at', '>=', now()->subMonth())
            ->count();
        $workoutsChange = $totalWorkouts > 0 ? $lastMonthWorkouts : 0;

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
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $daysOfWeek[$date->dayOfWeek];
            $workoutsCount = $workoutsByDay[$i];
            
            $height = 0;
            if ($maxWorkoutsInWeek > 0) {
                $height = min(100, ($workoutsCount / max($maxWorkoutsInWeek, 1)) * 100);
            }
            
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
            ->take(2);

        // Get recent workouts (last 5)
        $recentWorkouts = $user->workouts()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($workout) {
                $drills = $workout->drills ?? 'Workout';
                $type = explode(' ', $drills)[0] ?? 'Workout';
                if (strlen($type) > 20) {
                    $type = substr($type, 0, 20) . '...';
                }

                return [
                    'id' => $workout->id,
                    'type' => $type,
                    'duration' => $workout->duration . ' min',
                    'rpe' => $workout->rpe . '/10',
                    'date' => $workout->created_at->diffForHumans(),
                ];
            });

        // Placeholders
        $followers = 0;
        $followersChange = '+0 this month';
        $leaderboardRank = '#0';
        $rankChange = 'Top 0%';

        return $this->success([
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

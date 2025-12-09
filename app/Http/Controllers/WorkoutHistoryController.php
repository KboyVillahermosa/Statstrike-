<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkoutHistoryController extends Controller
{
    /**
     * Display the user's workout history and analytics.
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $workouts = $user->workouts()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate analytics
        $totalWorkouts = $user->workouts()->count();
        $totalMinutes = $user->workouts()->sum('duration');
        $averageRPE = $user->workouts()->avg('rpe');
        $totalRounds = $user->workouts()->sum('rounds');

        // Workouts by month (last 6 months)
        $monthlyStats = $user->workouts()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count, SUM(duration) as total_duration')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Average RPE by month
        $rpeStats = $user->workouts()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, AVG(rpe) as avg_rpe')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return Inertia::render('Workouts/History', [
            'workouts' => $workouts,
            'analytics' => [
                'totalWorkouts' => $totalWorkouts,
                'totalMinutes' => $totalMinutes,
                'averageRPE' => round($averageRPE ?? 0, 1),
                'totalRounds' => $totalRounds,
                'monthlyStats' => $monthlyStats,
                'rpeStats' => $rpeStats,
            ],
        ]);
    }
}

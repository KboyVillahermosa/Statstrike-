<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Challenge;
use App\Models\Post;
use App\Models\Workout;
use App\Models\ChallengeSession;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with system statistics.
     */
    public function index(Request $request): Response
    {
        // Total counts
        $totalUsers = User::count();
        $totalAdmins = User::where('is_admin', true)->count();
        $totalChallenges = Challenge::count();
        $activeChallenges = Challenge::where('is_active', true)->count();
        $totalPosts = Post::count();
        $totalWorkouts = Workout::count();
        $totalChallengeSessions = ChallengeSession::count();

        // Recent activity (last 7 days)
        $newUsers = User::where('created_at', '>=', now()->subDays(7))->count();
        $newPosts = Post::where('created_at', '>=', now()->subDays(7))->count();
        $newWorkouts = Workout::where('created_at', '>=', now()->subDays(7))->count();

        // Top users by points
        $topUsers = User::orderBy('points', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'email', 'points', 'is_admin']);

        // Recent challenges
        $recentChallenges = Challenge::withCount('users')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent posts
        $recentPosts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // User growth over last 30 days (for chart)
        $userGrowthData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $count = User::whereDate('created_at', $date)->count();
            $userGrowthData[] = [
                'date' => $date->format('M d'),
                'count' => $count,
                'total' => User::where('created_at', '<=', $date->endOfDay())->count(),
            ];
        }

        // Daily activity trends (last 7 days)
        $activityTrends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->startOfDay();
            $activityTrends[] = [
                'date' => $date->format('D'),
                'workouts' => Workout::whereDate('created_at', $date)->count(),
                'posts' => Post::whereDate('created_at', $date)->count(),
                'challenges' => ChallengeSession::whereDate('created_at', $date)->count(),
            ];
        }

        // User registration by month (last 6 months)
        $monthlyRegistrations = User::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                return [
                    'month' => date('M Y', strtotime($item->month . '-01')),
                    'count' => $item->count,
                ];
            });

        // Recent users (last 10)
        $recentUsers = User::orderBy('created_at', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'email', 'is_admin', 'points', 'created_at']);

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'users' => [
                    'total' => $totalUsers,
                    'admins' => $totalAdmins,
                    'regular' => $totalUsers - $totalAdmins,
                    'newThisWeek' => $newUsers,
                ],
                'challenges' => [
                    'total' => $totalChallenges,
                    'active' => $activeChallenges,
                    'inactive' => $totalChallenges - $activeChallenges,
                ],
                'community' => [
                    'totalPosts' => $totalPosts,
                    'newThisWeek' => $newPosts,
                ],
                'workouts' => [
                    'totalWorkouts' => $totalWorkouts,
                    'totalSessions' => $totalChallengeSessions,
                    'newThisWeek' => $newWorkouts,
                ],
            ],
            'topUsers' => $topUsers,
            'recentChallenges' => $recentChallenges,
            'recentPosts' => $recentPosts,
            'recentUsers' => $recentUsers,
            'charts' => [
                'userGrowth' => $userGrowthData,
                'activityTrends' => $activityTrends,
                'monthlyRegistrations' => $monthlyRegistrations,
            ],
        ]);
    }
}

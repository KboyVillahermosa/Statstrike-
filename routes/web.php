<?php

use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\WorkoutHistoryController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Workout routes
    Route::get('/workouts/log', [WorkoutController::class, 'create'])->name('workouts.log');
    Route::post('/workouts/log', [WorkoutController::class, 'store'])->name('workouts.store');
    Route::get('/workouts/history', [WorkoutHistoryController::class, 'index'])->name('workouts.history');

    Route::get('/workouts/templates', function () {
        return Inertia::render('Workouts/WorkoutTemplates');
    })->name('workouts.templates');

    Route::get('/workouts/tracker', function () {
        return Inertia::render('Workouts/MotionTracker');
    })->name('workouts.tracker');

    // Challenge routes
    Route::get('/challenges', [ChallengeController::class, 'index'])->name('challenges.index');
    Route::post('/challenges/{challenge}/join', [ChallengeController::class, 'join'])->name('challenges.join');
    Route::get('/challenges/{challenge}/session', [ChallengeController::class, 'startSession'])->name('challenges.session.start');
    Route::post('/challenges/{challenge}/session', [ChallengeController::class, 'storeSession'])->name('challenges.session.store');

    // Community routes
    Route::get('/community', [CommunityController::class, 'index'])->name('community');
    Route::post('/community/posts', [CommunityController::class, 'store'])->name('community.posts.store');
    Route::post('/community/posts/{post}/like', [CommunityController::class, 'toggleLike'])->name('community.posts.like');
    Route::post('/community/posts/{post}/comments', [CommunityController::class, 'storeComment'])->name('community.posts.comments.store');

    // AI Tools routes
    Route::get('/ai-tools/workout-generator', function () {
        return Inertia::render('AITools/WorkoutGenerator');
    })->name('ai-tools.workout-generator');

    Route::get('/ai-tools/progress-insights', function () {
        return Inertia::render('AITools/ProgressInsights');
    })->name('ai-tools.progress-insights');

    Route::get('/ai-tools/boxing-coach', function () {
        return Inertia::render('AITools/BoxingCoach');
    })->name('ai-tools.boxing-coach');
});

require __DIR__.'/auth.php';

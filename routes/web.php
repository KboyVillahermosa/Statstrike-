<?php

use App\Http\Controllers\ChallengeController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkoutHistoryController;
use App\Http\Controllers\WorkoutRoutineController;
use App\Http\Controllers\WorkoutController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutLogController;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Workout logging endpoints (session-authenticated)
Route::post('/workout/log-exercise', [WorkoutLogController::class, 'logExercise'])->middleware('auth');
Route::post('/workout/complete-day', [WorkoutLogController::class, 'completeDay'])->middleware('auth');
Route::get('/workout/history', [WorkoutLogController::class, 'history'])->middleware('auth')->name('workout.history');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Workout routes
    Route::get('/workouts/history', [WorkoutHistoryController::class, 'index'])->name('workouts.history');
    Route::post('/workouts/log', [WorkoutController::class, 'store'])->name('workouts.store');

    Route::get('/workouts/templates', [WorkoutRoutineController::class, 'index'])->name('workouts.templates');
    Route::post('/workouts/routines', [WorkoutRoutineController::class, 'store'])->name('workouts.routines.store');
    Route::put('/workouts/routines/{workoutRoutine}', [WorkoutRoutineController::class, 'update'])->name('workouts.routines.update');
    Route::delete('/workouts/routines/{workoutRoutine}', [WorkoutRoutineController::class, 'destroy'])->name('workouts.routines.destroy');

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

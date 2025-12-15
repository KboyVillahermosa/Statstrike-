<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminChallengeController;
use App\Http\Controllers\Admin\AdminCommunityController;
// use App\Http\Controllers\Admin\AdminWorkoutRoutineController; // Disabled - table dropped
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');

    // Challenge Management
    Route::get('/challenges', [AdminChallengeController::class, 'index'])->name('challenges.index');
    Route::get('/challenges/create', [AdminChallengeController::class, 'create'])->name('challenges.create');
    Route::post('/challenges', [AdminChallengeController::class, 'store'])->name('challenges.store');
    Route::get('/challenges/{challenge}', [AdminChallengeController::class, 'show'])->name('challenges.show');
    Route::get('/challenges/{challenge}/edit', [AdminChallengeController::class, 'edit'])->name('challenges.edit');
    Route::put('/challenges/{challenge}', [AdminChallengeController::class, 'update'])->name('challenges.update');
    Route::delete('/challenges/{challenge}', [AdminChallengeController::class, 'destroy'])->name('challenges.destroy');
    Route::patch('/challenges/{challenge}/toggle-active', [AdminChallengeController::class, 'toggleActive'])->name('challenges.toggle-active');

    // Community Post Management
    Route::get('/community/posts', [AdminCommunityController::class, 'index'])->name('community.posts.index');
    Route::get('/community/posts/{post}', [AdminCommunityController::class, 'show'])->name('community.posts.show');
    Route::delete('/community/posts/{post}', [AdminCommunityController::class, 'destroy'])->name('community.posts.destroy');

    // Workout Routine Management (disabled - table dropped)
    // Route::get('/workout-routines', [AdminWorkoutRoutineController::class, 'index'])->name('workout-routines.index');
    // Route::get('/workout-routines/{workoutRoutine}', [AdminWorkoutRoutineController::class, 'show'])->name('workout-routines.show');
    // Route::delete('/workout-routines/{workoutRoutine}', [AdminWorkoutRoutineController::class, 'destroy'])->name('workout-routines.destroy');

    // AI Tools Access (admin can access all AI tools)
    Route::get('/ai-tools/workout-generator', function () {
        return \Inertia\Inertia::render('AITools/WorkoutGenerator');
    })->name('ai-tools.workout-generator');

    Route::get('/ai-tools/progress-insights', function () {
        return \Inertia\Inertia::render('AITools/ProgressInsights');
    })->name('ai-tools.progress-insights');

    Route::get('/ai-tools/boxing-coach', function () {
        return \Inertia\Inertia::render('AITools/BoxingCoach');
    })->name('ai-tools.boxing-coach');
});


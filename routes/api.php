<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\WorkoutController;
use App\Http\Controllers\Api\WorkoutRoutineController;
use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\AIToolsController;

// Public routes - Health check for mobile connectivity
Route::get('/health', function () {
    $logPath = base_path('.cursor/debug.log');
    file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'A,B,C,E','location'=>'api.php:health','message'=>'health check endpoint hit','data'=>['timestamp'=>now()->toIso8601String(),'serverTime'=>time()*1000],'timestamp'=>time()*1000])."\n", FILE_APPEND);
    return response()->json(['status' => 'ok', 'message' => 'Laravel API is reachable', 'timestamp' => now()->toIso8601String()]);
});

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Workouts
    Route::get('/workouts/history', [WorkoutController::class, 'history']);
    Route::post('/workout/log-exercise', [WorkoutController::class, 'logExercise']);
    Route::post('/workout/complete-day', [WorkoutController::class, 'completeDay']);
    
    // Workout Routines
    Route::get('/workouts/templates', [WorkoutRoutineController::class, 'index']);
    Route::post('/workouts/routines', [WorkoutRoutineController::class, 'store']);
    Route::get('/workouts/routines/{workoutRoutine}', [WorkoutRoutineController::class, 'show']);
    Route::put('/workouts/routines/{workoutRoutine}', [WorkoutRoutineController::class, 'update']);
    Route::delete('/workouts/routines/{workoutRoutine}', [WorkoutRoutineController::class, 'destroy']);
    
    // Challenges
    Route::get('/challenges', [ChallengeController::class, 'index']);
    Route::post('/challenges/{challenge}/join', [ChallengeController::class, 'join']);
    Route::get('/challenges/{challenge}/session', [ChallengeController::class, 'startSession']);
    Route::post('/challenges/{challenge}/session', [ChallengeController::class, 'storeSession']);
    
    // Community
    Route::get('/community', [CommunityController::class, 'index']);
    Route::post('/community/posts', [CommunityController::class, 'store']);
    Route::post('/community/posts/{post}/like', [CommunityController::class, 'toggleLike']);
    Route::post('/community/posts/{post}/comments', [CommunityController::class, 'storeComment']);
    
    // AI Tools
    Route::post('/ai-tools/workout-generator', [AIToolsController::class, 'workoutGenerator']);
    Route::post('/ai-tools/boxing-coach', [AIToolsController::class, 'boxingCoach']);
    Route::post('/ai-tools/progress-insights', [AIToolsController::class, 'progressInsights']);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
});


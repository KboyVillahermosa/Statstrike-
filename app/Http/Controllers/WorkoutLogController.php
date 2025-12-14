<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ExerciseLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WorkoutLogController extends Controller
{
    public function logExercise(Request $request)
    {
        $data = $request->validate([
            'routine_id' => 'nullable|integer',
            'program_id' => 'nullable|integer',
            'exercise_title' => 'nullable|string',
            'exercise_key' => 'nullable|string',
            'reps' => 'nullable|integer',
            'duration_seconds' => 'nullable|integer',
            'tracked' => 'nullable|boolean',
            'accuracy' => 'nullable|numeric',
            'metadata' => 'nullable|array',
        ]);

        $log = ExerciseLog::create(array_merge($data, [
            'user_id' => Auth::id(),
        ]));

        return response()->json(['ok' => true, 'log_id' => $log->id]);
    }

    public function completeDay(Request $request)
    {
        $data = $request->validate([
            'routine_id' => 'nullable|integer',
            'date' => 'required|date',
            'summary' => 'nullable|array',
        ]);

        // Minimal implementation: store a summary record as an ExerciseLog with metadata.type = 'day_summary'
        $log = ExerciseLog::create([
            'user_id' => Auth::id(),
            'routine_id' => $data['routine_id'] ?? null,
            'exercise_title' => 'DAY_SUMMARY',
            'exercise_key' => 'day_summary',
            'metadata' => array_merge($data['summary'] ?? [], ['date' => $data['date'], 'type' => 'day_summary']),
        ]);

        return response()->json(['ok' => true, 'summary_id' => $log->id]);
    }

    // Show recent exercise logs for the authenticated user
    public function history(Request $request)
    {
        $logs = ExerciseLog::where('user_id', Auth::id())->orderBy('created_at', 'desc')->take(200)->get();

        return Inertia::render('Workouts/History', [
            'logs' => $logs,
        ]);
    }
}

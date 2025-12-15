<?php

namespace App\Http\Controllers\Api;

use App\Models\ExerciseLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends BaseController
{
    /**
     * Get workout history.
     */
    public function history(Request $request): JsonResponse
    {
        $logs = ExerciseLog::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(200)
            ->get();

        return $this->success($logs);
    }

    /**
     * Log an exercise.
     */
    public function logExercise(Request $request): JsonResponse
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

        return $this->success([
            'log_id' => $log->id,
            'log' => $log,
        ], 'Exercise logged successfully');
    }

    /**
     * Complete a workout day.
     */
    public function completeDay(Request $request): JsonResponse
    {
        $data = $request->validate([
            'routine_id' => 'nullable|integer',
            'date' => 'required|date',
            'summary' => 'nullable|array',
        ]);

        $log = ExerciseLog::create([
            'user_id' => Auth::id(),
            'routine_id' => $data['routine_id'] ?? null,
            'exercise_title' => 'DAY_SUMMARY',
            'exercise_key' => 'day_summary',
            'metadata' => array_merge($data['summary'] ?? [], [
                'date' => $data['date'],
                'type' => 'day_summary'
            ]),
        ]);

        return $this->success([
            'summary_id' => $log->id,
            'summary' => $log,
        ], 'Day completed successfully');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    /**
     * Store a completed workout in the user's history.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Use very lenient parsing so a bad payload never blocks logging
        $drills = (string) $request->input('drills', 'Workout session');
        if (strlen($drills) > 1000) {
            $drills = substr($drills, 0, 1000);
        }

        $rounds = (int) $request->input('rounds', 1);
        if ($rounds < 1) {
            $rounds = 1;
        }

        $duration = (int) $request->input('duration', 1);
        if ($duration < 1) {
            $duration = 1;
        }

        $rpe = (int) $request->input('rpe', 7);
        if ($rpe < 1) {
            $rpe = 1;
        } elseif ($rpe > 10) {
            $rpe = 10;
        }

        Workout::create([
            'user_id' => $user->id,
            'drills' => $drills,
            'rounds' => $rounds,
            'duration' => $duration,
            'rpe' => $rpe,
        ]);

        return back();
    }
}



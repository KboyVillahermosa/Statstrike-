<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends BaseController
{
    /**
     * Get user profile.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo' => $user->profile_photo ? Storage::url($user->profile_photo) : null,
            'fitness_goal' => $user->fitness_goal,
            'experience_level' => $user->experience_level,
            'subscription_tier' => $user->subscription_tier,
            'points' => $user->points,
            'device_capability' => $user->device_capability,
            'mediapipe_supported' => $user->mediapipe_supported,
        ]);
    }

    /**
     * Update user profile.
     */
    public function update(ProfileUpdateRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Store new photo
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        } else {
            // Don't overwrite existing photo if not uploading new one
            unset($data['profile_photo']);
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $this->success([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'profile_photo' => $user->profile_photo ? Storage::url($user->profile_photo) : null,
            'fitness_goal' => $user->fitness_goal,
            'experience_level' => $user->experience_level,
            'subscription_tier' => $user->subscription_tier,
            'points' => $user->points,
        ], 'Profile updated successfully');
    }

    /**
     * Delete user account.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required'],
        ]);

        // Verify password manually for API
        if (!\Illuminate\Support\Facades\Hash::check($request->password, $request->user()->password)) {
            return $this->error('Invalid password', ['password' => ['The provided password is incorrect.']], 422);
        }

        $user = $request->user();

        // Delete profile photo if exists
        if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Revoke all tokens
        $user->tokens()->delete();

        $user->delete();

        return $this->success(null, 'Account deleted successfully');
    }
}

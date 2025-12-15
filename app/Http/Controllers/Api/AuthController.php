<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends BaseController
{
    /**
     * Register a new user.
     */
    public function register(Request $request): JsonResponse
    {
        $logPath = base_path('.cursor/debug.log');
        // #region agent log
        file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'A,B,C,D,E','location'=>'AuthController.php:16','message'=>'register() entry','data'=>['method'=>$request->method(),'url'=>$request->fullUrl(),'ip'=>$request->ip(),'hasName'=>$request->has('name'),'hasEmail'=>$request->has('email'),'hasPassword'=>$request->has('password')],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        // #endregion

        // #region agent log
        file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'D','location'=>'AuthController.php:23','message'=>'before validation','data'=>['requestData'=>['name'=>$request->input('name'),'email'=>$request->input('email'),'hasPasswordConfirmation'=>$request->has('password_confirmation')]],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        // #endregion
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
            // #region agent log
            file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'D','location'=>'AuthController.php:32','message'=>'after validation success','data'=>['validatedKeys'=>array_keys($validated)],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            // #endregion
        } catch (\Illuminate\Validation\ValidationException $e) {
            // #region agent log
            file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'D','location'=>'AuthController.php:35','message'=>'validation failed','data'=>['errors'=>$e->errors()],'timestamp'=>time()*1000])."\n", FILE_APPEND);
            // #endregion
            throw $e;
        }

        // #region agent log
        file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'A,B,C','location'=>'AuthController.php:39','message'=>'before user creation','data'=>['email'=>$validated['email']],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        // #endregion
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        // #region agent log
        file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'A,B,C','location'=>'AuthController.php:46','message'=>'after user creation','data'=>['userId'=>$user->id],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        // #endregion

        $token = $user->createToken('mobile-app')->plainTextToken;
        // #region agent log
        file_put_contents($logPath, json_encode(['sessionId'=>'debug-session','runId'=>'run1','hypothesisId'=>'A,B,C','location'=>'AuthController.php:50','message'=>'returning success response','data'=>['userId'=>$user->id,'hasToken'=>!empty($token)],'timestamp'=>time()*1000])."\n", FILE_APPEND);
        // #endregion

        return $this->success([
            'user' => $user,
            'token' => $token,
        ], 'User registered successfully', 201);
    }

    /**
     * Login user and return token.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return $this->success([
            'user' => $user,
            'token' => $token,
        ]);
    }

    /**
     * Logout user (revoke current token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully');
    }

    /**
     * Get authenticated user.
     */
    public function user(Request $request): JsonResponse
    {
        return $this->success($request->user());
    }

    /**
     * Send password reset email.
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        // TODO: Implement password reset logic
        // Send reset email, etc.

        return $this->success(null, 'Password reset link sent to your email.');
    }
}


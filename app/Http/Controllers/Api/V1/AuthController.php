<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRegisterRequest;
use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseApiController
{
    /**
     * Register a new user.
     *
     *
     * @param UserRegisterRequest $request
     *
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request): JsonResponse
    {
        $user = User::create($request->validated());

        SendWelcomeEmailJob::dispatch($user->id);

        return $this->created($user, 'User registered successfully.', 201);
    }

    /**
     * Login a user.
     *
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->success(['access_token' => $token, 'token_type' => 'Bearer'], 'Login successfully.');
    }

    /**
     * Logout a user.
     *
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        if ($user) {
            $user->tokens()->delete();
            return $this->success(null, 'Logged out successfully', 200);
        }

        return $this->error('Failed to log out', ['message' => 'No authenticated user found'], 401);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\UserRegisterRequest;
use App\Jobs\SendWelcomeEmailJob;
use App\Models\User;
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
}

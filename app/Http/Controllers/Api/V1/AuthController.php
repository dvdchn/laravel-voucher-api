<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\UserRegisterRequest;
use App\Models\User;
use App\Services\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends BaseApiController
{
    protected $voucherService;

    public function __construct(VoucherService $voucherService)
    {
        $this->voucherService = $voucherService;
    }
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

        $this->voucherService->createVoucherForUser($user->id);

        return $this->created($user, 'User registered successfully.', 201);
    }
}

<?php
use App\Http\Controllers\Api\v1\AuthController as AuthControllerV1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthControllerV1::class, 'register']);
    Route::post('/login', [AuthControllerV1::class, 'login']);
    Route::post('/logout', [AuthControllerV1::class, 'logout'])->middleware('auth:sanctum');

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
});
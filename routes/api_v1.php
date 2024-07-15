<?php
use App\Http\Controllers\Api\v1\AuthController as AuthControllerV1;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthControllerV1::class, 'register']);
});
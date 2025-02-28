<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\TownshipController;
use App\Http\Controllers\Api\StateRegionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::resources([
        'state-regions' => StateRegionController::class,
        'districts' => DistrictController::class,
        'townships' => TownshipController::class
    ]);

    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user/update', [UserController::class, 'update']);
    Route::get('/user/types', [UserController::class, 'userTypes']);
    Route::get('/user/location', [UserController::class, 'location']);
});

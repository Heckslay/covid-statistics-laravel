<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\StatisticController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')
    ->middleware('auth:sanctum')
    ->group(function () {
        Route::get('/get-countries', [StatisticController::class, 'index']);
        Route::get('/get-summary', [StatisticController::class, 'getSummary']);
    });

Route::prefix('auth')
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

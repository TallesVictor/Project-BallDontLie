<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlayerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::controller(PlayerController::class)->prefix('player')->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::get('/{player}', 'show');
        Route::put('/{player}', 'update');
        Route::delete('/{player}', 'destroy')->middleware(\App\Http\Middleware\IsAdminMiddleware::class);
    });



    Route::post('/logout', [AuthController::class, 'logout']);
});


<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

// --- LOGIN ---
Route::post('/login', [AuthController::class, 'login']);


// --- Requires login ---
Route::middleware('auth:sanctum')->group(function () {
    // --- LOGOUT ---
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Current User ---
    Route::get('/user', [UserController::class, 'me']);
});

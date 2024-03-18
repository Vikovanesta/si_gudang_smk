<?php

use App\Http\Controllers\AuthController;
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

Route::group(['prefix' => 'v1'], function(){
    Route::post('/register/students', [AuthController::class,'registerStudent'])->name('registerStudent');
    Route::post('/login', [AuthController::class,'login'])->name('login');
    // Route::post('/register', [AuthController::class,'register'])->name('register');
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v1'], function () {
    Route::post('/register/students/{studentRegistration}', [AuthController::class,'verifyRegistration'])->name('verifyRegistration');

    Route::post('/logout', [AuthController::class,'logout'])->name('logout');
});

/**
 * Fallback route
 * 
 * This route will be used if the requested route is not found.
 * 
 * @group Fallback
 */
Route::fallback(function(){
    return response()->json([
        'message' => 'Endpoint not found'
    ],404);
});

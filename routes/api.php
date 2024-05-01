<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\BorrowingRequestController;
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

Route::group(['middleware' => ['auth.opt'], 'prefix' => 'v1'], function(){
    Route::post('/register/students', [AuthController::class,'registerStudent'])->name('registerStudent');
    Route::post('/login', [AuthController::class,'login'])->name('login');
    // Route::post('/register', [AuthController::class,'register'])->name('register');
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'v1'], function () {
    Route::get('/register/students', [AuthController::class,'indexRegistration'])->name('indexRegistration');
    Route::post('/register/students/{studentRegistration}', [AuthController::class,'verifyRegistration'])->name('verifyRegistration');
    Route::post('/register/employees', [AuthController::class,'registerEmployee'])->name('registerEmployee');

    Route::get('/borrowings', [BorrowingController::class,'index'])->name('indexBorrowings');

    Route::get('/borrowing-requests', [BorrowingRequestController::class,'index'])->name('indexBorrowingRequests');
    Route::post('/borrowing-requests', [BorrowingRequestController::class,'store'])->name('storeBorrowingRequest');
    Route::put('/borrowing-requests/{borrowingRequest}', [BorrowingRequestController::class,'handle'])->name('handleBorrowingRequest');

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

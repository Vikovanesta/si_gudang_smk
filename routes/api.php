<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowedItemController;
use App\Http\Controllers\BorrowingRequestController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LaboranController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

    Route::get('/students', [StudentController::class,'index'])->name('indexStudents');
    Route::post('/students', [StudentController::class,'store'])->name('storeStudent');
    Route::get('/students/{student}', [StudentController::class,'show'])->name('showStudent');
    Route::put('/students/{student}', [StudentController::class,'update'])->name('updateStudent');
    Route::delete('/students/{student}', [StudentController::class,'delete'])->name('deleteStudent');

    Route::get('/teachers', [TeacherController::class,'index'])->name('indexTeachers');
    Route::post('/teachers', [TeacherController::class,'store'])->name('storeTeacher');
    Route::get('/teachers/{teacher}', [TeacherController::class,'show'])->name('showTeacher');
    Route::put('/teachers/{teacher}', [TeacherController::class,'update'])->name('updateTeacher');
    Route::delete('/teachers/{teacher}', [TeacherController::class,'delete'])->name('deleteTeacher');

    Route::get('/laborans', [LaboranController::class,'index'])->name('indexLaborans');
    Route::post('/laborans', [LaboranController::class,'store'])->name('storeLaboran');
    Route::get('/laborans/{laboran}', [LaboranController::class,'show'])->name('showLaboran');
    Route::put('/laborans/{laboran}', [LaboranController::class,'update'])->name('updateLaboran');
    Route::delete('/laborans/{laboran}', [LaboranController::class,'delete'])->name('deleteLaboran');

    Route::get('/me/borrowing-requests', [BorrowingRequestController::class,'indexAcademic'])->name('indexAcademicBorrowingRequests');
    Route::get('/me/borrowed-items', [BorrowedItemController::class,'indexAcademic'])->name('indexAcademicBorrowedItems');

    Route::get('/borrowing-requests', [BorrowingRequestController::class,'indexManagement'])->name('indexManagementBorrowingRequests');
    Route::post('/borrowing-requests', [BorrowingRequestController::class,'store'])->name('storeBorrowingRequest');
    Route::put('/borrowing-requests/{borrowingRequest}', [BorrowingRequestController::class,'handle'])->name('handleBorrowingRequest');

    Route::get('/borrowed-items', [BorrowedItemController::class,'indexManagement'])->name('indexManagementBorrowedItems');
    Route::put('/borrowed-items/{borrowedItem}', [BorrowedItemController::class,'update'])->name('updateBorrowedItem');

    Route::get('/items', [ItemController::class,'index'])->name('indexItems');
    Route::post('/items', [ItemController::class,'store'])->name('storeItem');
    Route::delete('/items/{item}', [ItemController::class,'delete'])->name('deleteItem');

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

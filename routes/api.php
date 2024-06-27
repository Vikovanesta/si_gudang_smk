<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BorrowedItemController;
use App\Http\Controllers\BorrowingRequestController;
use App\Http\Controllers\BorrowingRequestStatusController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LaboranController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolSubjectController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\WarehouseController;
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
    Route::post('/login', [AuthController::class,'login'])->name('auth');
    Route::get('/classes', [SchoolClassController::class,'index'])->name('indexClasses');
    Route::get('/borrowing-request-statuses', [BorrowingRequestStatusController::class,'index'])->name('indexBorrowingRequestStatuses');
    Route::get('/subjects', [SchoolSubjectController::class,'index'])->name('indexSubjects');

    // Route::post('/register', [AuthController::class,'register'])->name('register');

    Route::get('/postman', function () {
        return response()->file(storage_path('/app/scribe/collection.json'));
    })->name('scribe.postman');

    Route::get('/openapi', function () {
        return response()->file(storage_path('/app/scribe/openapi.yaml'));
    })->name('scribe.openapi');
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

    Route::get('/me', [AuthController::class,'me'])->name('me');
    Route::get('/me/borrowing-requests', [BorrowingRequestController::class,'indexAcademic'])->name('indexAcademicBorrowingRequests');
    Route::get('/me/borrowed-items', [BorrowedItemController::class,'indexAcademic'])->name('indexAcademicBorrowedItems');
    Route::get('/me/carts', [CartController::class,'index'])->name('indexCarts');
    Route::post('/me/carts', [CartController::class,'store'])->name('storeCart');
    Route::delete('/me/carts/{item}', [CartController::class,'destroy'])->name('destroyCart');

    Route::get('/borrowing-requests', [BorrowingRequestController::class,'indexManagement'])->name('indexManagementBorrowingRequests');
    Route::get('/borrowing-requests/{borrowingRequest}', [BorrowingRequestController::class,'show'])->name('showBorrowingRequest');
    Route::post('/borrowing-requests', [BorrowingRequestController::class,'store'])->name('storeBorrowingRequest');
    Route::put('/borrowing-requests/{borrowingRequest}', [BorrowingRequestController::class,'handle'])->name('handleBorrowingRequest');

    Route::get('/borrowed-items', [BorrowedItemController::class,'indexManagement'])->name('indexManagementBorrowedItems');
    Route::get('/borrowed-items/{borrowedItem}', [BorrowedItemController::class,'show'])->name('showBorrowedItem');
    Route::put('/borrowed-items/{borrowedItem}', [BorrowedItemController::class,'update'])->name('updateBorrowedItem');

    Route::get('/items', [ItemController::class,'index'])->name('indexItems');
    Route::post('/items', [ItemController::class,'store'])->name('storeItem');
    Route::put('/items/{item}', [ItemController::class,'update'])->name('updateItem');
    Route::delete('/items/{item}', [ItemController::class,'delete'])->name('deleteItem');

    Route::get('/items/categories', [ItemCategoryController::class,'index'])->name('indexItemCategories');
    Route::post('/items/categories', [ItemCategoryController::class,'store'])->name('storeItemCategory');
    Route::put('/items/categories/{itemCategory}', [ItemCategoryController::class,'update'])->name('updateItemCategory');
    Route::delete('/items/categories/{itemCategory}', [ItemCategoryController::class,'destroy'])->name('destroyItemCategory');

    Route::get('/warehouses', [WarehouseController::class,'index'])->name('indexWarehouses');
    Route::post('/warehouses', [WarehouseController::class,'store'])->name('storeWarehouse');
    Route::put('/warehouses/{warehouse}', [WarehouseController::class,'update'])->name('updateWarehouse');
    Route::delete('/warehouses/{warehouse}', [WarehouseController::class,'delete'])->name('deleteWarehouse');

    Route::get('/materials', [MaterialController::class,'index'])->name('indexMaterials');
    Route::post('/materials', [MaterialController::class,'store'])->name('storeMaterial');
    Route::put('/materials/{material}', [MaterialController::class,'update'])->name('updateMaterial');
    Route::delete('/materials/{material}', [MaterialController::class,'delete'])->name('deleteMaterial');

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

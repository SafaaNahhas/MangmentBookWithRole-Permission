<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryBook\BookController;
use App\Http\Controllers\RolePermission\UserController;
use App\Http\Controllers\RolePermission\RolesController;
use App\Http\Controllers\CategoryBook\CategoryController;
use App\Http\Controllers\RolePermission\PermissionsController;


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



/**
 * Routes for user authentication.
 */
Route::post('register', [AuthController::class, 'register'])->middleware('permission:register');
Route::post('login', [AuthController::class, 'login'])->middleware('permission:login');


Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->middleware('permission:logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('permission:refresh');
    Route::get('/me', [AuthController::class, 'me'])->middleware('permission:me');


Route::get('categories/getSoftDeletedCategory', [CategoryController::class, 'getSoftDeletedCategory'])->middleware('permission:get-SoftDeleted-Category');

Route::get('categories', [CategoryController::class, 'index'])->middleware('permission:get-categories');
Route::post('categories', [CategoryController::class, 'store'])->middleware('permission:store-category');
Route::put('categories/{id}', [CategoryController::class, 'update'])->middleware('permission:update-category');
Route::get('categories/{id}', [CategoryController::class, 'show'])->middleware('permission:show-category');
Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->middleware('permission:destroy-category');


Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->middleware('permission:restore-category');
Route::delete('/categories/{id}/forceDestroy', [CategoryController::class, 'forceDestroy'])->middleware('permission:forceDestroy-category');

Route::get('books/getSoftDeletedBook', [BookController::class, 'getSoftDeletedBook'])->middleware('permission:get-SoftDeleted-Book');

Route::get('books',  [BookController::class,'index'])->middleware('permission:get-books');
Route::post('books',  [BookController::class,'store'])->middleware('permission:store-book');
Route::put('books/{id}',  [BookController::class,'update'])->middleware('permission:update-book');
Route::get('books/{id}',  [BookController::class,'show'])->middleware('permission:show-book');
Route::delete('books/{id}',  [BookController::class,'destroy'])->middleware('permission:destroy-book');

Route::get('categories/{categoryId}/books', [BookController::class, 'indexByCategory'])->middleware('permission:view-books with categories');
Route::post('/books/{id}/restore', [BookController::class, 'restore'])->middleware('permission:restore-book');
Route::delete('/books/{id}/forceDestroy', [BookController::class, 'forceDestroy'])->middleware('permission:forceDestroy-book');


    // إدارة المستخدمين
    Route::get('/users', [UserController::class, 'index'])->middleware('permission:view-users');
    Route::post('/users', [UserController::class, 'store'])->middleware('permission:create-user');
    Route::put('/users/{id}', [UserController::class, 'update'])->middleware('permission:edit-user');
    Route::get('/users/{id}', [UserController::class, 'show'])->middleware('permission:get-user');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('permission:delete-user');
    Route::post('/users/{userId}/assign-role', [UserController::class, 'assignRole'])->middleware('permission:assign-Role');

    // إدارة الأدوار
    Route::get('/roles', [RolesController::class, 'index'])->middleware('permission:view-roles');
    Route::post('/roles', [RolesController::class, 'store'])->middleware('permission:create-role');
    Route::put('/roles/{id}', [RolesController::class, 'update'])->middleware('permission:edit-role');
    Route::delete('/roles/{id}', [RolesController::class, 'destroy'])->middleware('permission:delete-role');
    Route::get('roles/{id}', [RolesController::class, 'show'])->middleware('permission:show-role');;


    // إدارة الصلاحيات
    Route::get('/permissions', [PermissionsController::class, 'index'])->middleware('permission:view-permissions');
    Route::post('/permissions', [PermissionsController::class, 'store'])->middleware('permission:create-permission');
    Route::put('/permissions/{id}', [PermissionsController::class, 'update'])->middleware('permission:edit-permission');
    Route::get('/permissions/{id}', [PermissionsController::class, 'show'])->middleware('permission:show-permission');
    Route::delete('/permissions/{id}', [PermissionsController::class, 'destroy'])->middleware('permission:delete-permission');

    Route::post('/roles/{role}/permissions', [RolesController::class, 'assignPermissions'])->middleware('permission:assign-permissions');
    Route::delete('/roles/{role}/permissions/{permission}', [RolesController::class, 'removePermission'])->middleware('permission:remove-permissions');

});

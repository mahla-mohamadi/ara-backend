<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;


/////// token protect
//Route::middleware('auth:api')->group(function () {
//    Route::post('/token', [AuthController::class, 'checkToken']);
//    Route::post('/logout', [AuthController::class, 'logout']);
//    Route::post('/profile', [AuthController::class, 'getAuthenticatedUser']);
//    Route::post('/updateprofile', [AuthController::class, 'updateProfile']);
//});
//
///// is admin middleware
//Route::middleware(['auth:api', EnsureUserIsAdmin::class])->group(function () {
//    Route::post('/listusers', [UserController::class, 'listUsers']);
//});
/// complete profile route
/// my profile route
/// logout route
/// address add route


///// token protect
// Route::middleware('auth:api')->group(function () {
//     Route::post('/listusers', [UserController::class, 'listUsers']);
// });

///// token protect with route grouping
// Route::middleware('auth:api')->prefix('user')->group(function () {
//     Route::post('/getall', [UserController::class, 'listUsers']);
//     Route::post('/get', [UserController::class, 'getSingleUser']);
//     Route::post('/update', [UserController::class, 'updateUser']);
// });

Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/getPhones', [AuthController::class, 'getPhones'])->name('getPhones');
Route::post('/sendOtp', [AuthController::class, 'sendOtp'])->name('sendOtp');
Route::post('/loginWithOtp', [AuthController::class, 'loginWithOtp'])->name('loginWithOtp');
Route::post('/check', [UserController::class, 'check'])->name('check')->middleware('auth:api');
Route::post('/checkPermission', [UserController::class, 'checkPermission'])->name('checkPermission')->middleware('auth:api','role:مدیریت');

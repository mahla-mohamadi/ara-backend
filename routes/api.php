<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsAdmin;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
// Route::post('/add', [TaxonomyController::class, 'createTaxonomy']);


Route::post('/sendotp', [AuthController::class, 'sendOtp']);
Route::post('/loginotp', [AuthController::class, 'loginWithPhoneOtp']);
///// token protect
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/profile', [AuthController::class, 'getAuthenticatedUser']);
    Route::post('/updateprofile', [AuthController::class, 'updateProfile']);
});

/// is admin middleware
Route::middleware(['auth:api', EnsureUserIsAdmin::class])->group(function () {
    Route::post('/listusers', [UserController::class, 'listUsers']);
});
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
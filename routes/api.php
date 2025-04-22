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
Route::post('/listusers', [UserController::class, 'listUsers']);

// Route::middleware(['auth:api', EnsureUserIsAdmin::class])->group(function () {
//     Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
// });
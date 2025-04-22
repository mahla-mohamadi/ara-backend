<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');
// Route::post('/add', [TaxonomyController::class, 'createTaxonomy']);


Route::post('/sendotp', [AuthController::class, 'sendOtp']);
Route::post('/loginotp', [AuthController::class, 'loginWithPhoneOtp']);
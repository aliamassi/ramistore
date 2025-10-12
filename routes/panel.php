<?php

use App\Http\Controllers\Panel\CategoryController;
use App\Http\Controllers\Panel\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
//Route::get('/user', function () {
//    return response()->json([
//        'status' => true,
//        'user' => \Illuminate\Support\Facades\Auth::guard('panel')->user()
//    ]);
//});
require __DIR__ . '/panel/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/user', fn() => request()->user());     // used by Nuxt to check auth
    Route::apiResource('product', ProductController::class);
    Route::post('product/{product}/images', [ProductController::class,'uploadImage']);
    Route::get('product/{product}/images', [ProductController::class,'getImages']);
    Route::apiResource('category', CategoryController::class);
});
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//
//    return $request->user();
//});

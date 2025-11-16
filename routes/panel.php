<?php

use App\Http\Controllers\Panel\AdminController;
use App\Http\Controllers\Panel\CategoryController;
use App\Http\Controllers\Panel\ProductController;
use App\Http\Controllers\Panel\SettingsController;
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
    Route::post('product/variant', [ProductController::class,'storeVariant']);
    Route::put('product/{product}/visibility', [ProductController::class,'changeVisibility']);
    Route::put('product/variant/{product}/', [ProductController::class,'updateVariant']);
    Route::put('product/type/{product}/', [ProductController::class,'updateType']);
    Route::get('product/{product}/images', [ProductController::class,'getImages']);
    Route::delete('product/variant/{variant}', [ProductController::class,'deleteVariant']);
    Route::apiResource('category', CategoryController::class);
    Route::put('category/{category}/visibility', [CategoryController::class,'changeVisibility']);

    Route::post('admin/upload', [AdminController::class,'uploadImage']);
    Route::get('settings', [SettingsController::class,'index']);
    Route::post('settings', [SettingsController::class,'store']);

});
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//
//    return $request->user();
//});

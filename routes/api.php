<?php

use App\Http\Controllers\CategoryApiController;
use App\Http\Controllers\ProductApiController;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/category', [CategoryApiController::class, 'index']);
Route::post('/category/store', [CategoryApiController::class, 'store']);
Route::post('/category/update/{categories}', [CategoryApiController::class, 'update']);
Route::post('/category/destroy/{categories}', [CategoryApiController::class, 'destroy']);

Route::get('/product', [ProductApiController::class, 'index']);
Route::post('/product/store', [ProductApiController::class, 'store']);
Route::post('/product/update/{product}', [ProductApiController::class, 'update']);
Route::post('/product/destroy/{product}', [ProductApiController::class, 'destroy']);
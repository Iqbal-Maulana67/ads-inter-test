<?php

use App\Http\Controllers\CategoryController;
use App\Models\Categories;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('pages.dashboard');
});

route::get('/categories', [CategoryController::class, 'index'])->name('category');
route::post('/categories/store', [CategoryController::class, 'store'])->name('category.store');
route::post('/categories/update/{category}', [CategoryController::class, 'update'])->name('category.update');
route::post('/categories/delete/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

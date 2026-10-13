<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', [PageController::class, 'hello']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
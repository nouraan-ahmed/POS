<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => LaravelLocalization::setLocale()], function () {
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
        Route::get('/index', [DashboardController::class, 'index']);

        //users routes

        Route::get('users/index', [UserController::class, 'index'])->name('index');
        Route::get('users/create', [UserController::class, 'create']);
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::post('users/store', [UserController::class, 'store']);
        Route::delete('users/{user}/destroy', [UserController::class, 'destroy'])->name('destroy');
        Route::put('users/{user}/update', [UserController::class, 'update'])->name('update');
        //Route::resource('users', 'App\Http\Controllers\Dashboard\UserController')->except('show');
        //Route::resource('users', UserController::class)->except('show');

        //categories routes

        Route::get('categories/index', [CategoryController::class, 'index'])->name('categories');
        Route::get('categories/create', [CategoryController::class, 'create']);
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('categories/store', [CategoryController::class, 'store']);
        Route::delete('categories/{category}/destroy', [CategoryController::class, 'destroy'])->name('destroy');
        Route::put('categories/{category}/update', [CategoryController::class, 'update'])->name('update');


        //products routes

        Route::get('products/index', [ProductController::class, 'index'])->name('products');
        Route::get('products/create', [ProductController::class, 'create']);
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('products/store', [ProductController::class, 'store'])->name('store');
        Route::delete('products/{product}/destroy', [ProductController::class, 'destroy'])->name('destroy');
        Route::put('products/{product}/update', [ProductController::class, 'update'])->name('update');
    });
});

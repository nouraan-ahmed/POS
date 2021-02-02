<?php

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => LaravelLocalization::setLocale()], function () {
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    Route::prefix('dashboard')->name('dashboard.')->middleware(['auth'])->group(function () {
        Route::get('/index', [DashboardController::class, 'index']);
        Route::get('users/index', [UserController::class, 'index'])->name('index');
        Route::get('users/create', [UserController::class, 'create']);
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::post('users/store', [UserController::class, 'store']);
        Route::delete('users/{user}/destroy', [UserController::class, 'destroy'])->name('destroy');
        Route::put('users/{user}/update', [UserController::class, 'update'])->name('update');
        //Route::resource('users', 'App\Http\Controllers\Dashboard\UserController')->except('show');
        //Route::resource('users', UserController::class)->except('show');
    });
});

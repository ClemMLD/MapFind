<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BlockedUserController;

require __DIR__ . '/auth.php';

Route::get('/', [WelcomeController::class, 'show'])->name('welcome');

Route::middleware('auth')->group(function () {
    Route::resource('listings', ListingController::class);
    Route::group(['listing'], function () {
        Route::post('/boost', [ListingController::class, 'boost'])->name('listings.boost');
    });
    Route::resource('messages', MessageController::class);
    Route::resource('categories', CategoryController::class)->only(['index']);
    Route::resource('users', UserController::class)->only(['show']);
    Route::resource('blocked-users', BlockedUserController::class);
    Route::resource('account', AccountController::class);
    Route::group(['account'], function () {
        Route::match(['POST', 'DELETE'], '/avatar', [AccountController::class, 'avatar'])->name('account.avatar');
        Route::get('/upgrade', [AccountController::class, 'upgrade'])->name('account.upgrade');
    });
});
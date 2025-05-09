<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\BlockedUserController;

require __DIR__ . '/auth.php';

Route::get('/', [WelcomeController::class, 'show'])->name('welcome');

Route::middleware('auth')->group(function () {
    Route::resource('listings', ListingController::class);
    Route::prefix('listings')->group(function () {
        Route::post('/boost', [ListingController::class, 'boost'])->name('listings.boost');
    });
    Route::resource('messages', MessageController::class);
    Route::resource('categories', CategoryController::class)->only(['index']);
    Route::resource('users', UserController::class)->only(['show']);
    Route::resource('blocked-users', BlockedUserController::class);
    Route::prefix('account')->group(function () {
        Route::match(['POST', 'DELETE'], '/avatar', [AccountController::class, 'avatar'])->name('account.avatar');
        Route::get('/listings', [AccountController::class, 'listings'])->name('account.listings');
        Route::get('/upgrade', [AccountController::class, 'upgrade'])->name('account.upgrade');
    });
    Route::resource('favorites', FavoriteController::class);
    Route::resource('account', AccountController::class);
});
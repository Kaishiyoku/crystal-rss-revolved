<?php

use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\FeedDiscovererController;
use App\Http\Controllers\Api\FeedUrlDiscovererController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');

    Route::delete('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::resource('categories', CategoryController::class);
    Route::put('/feeds/mark-all-as-read', \App\Http\Controllers\Api\MarkAllUnreadFeedItemsAsReadController::class)->name('mark-all-as-read');
    Route::resource('feeds', FeedController::class);
    Route::put('/feeds/{feedItem}/toggle', \App\Http\Controllers\Api\ToggleFeedItemController::class)->name('toggle-feed-item');

    Route::post('discover-feed', FeedDiscovererController::class)->name('discover-feed');
    Route::post('discover-feed-urls', FeedUrlDiscovererController::class)->name('discover-feed-urls');

    Route::get('feed-items', DashboardController::class);

    Route::middleware('administrate')->prefix('admin')->as('admin.')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'destroy']);
    });
});

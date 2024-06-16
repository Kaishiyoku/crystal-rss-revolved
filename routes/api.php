<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\FeedController;
use App\Http\Controllers\Api\FeedDiscovererController;
use App\Http\Controllers\Api\FeedUrlDiscovererController;
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
    Route::delete('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::resource('categories', CategoryController::class);
    Route::put('/feeds/mark-all-as-read', \App\Http\Controllers\Api\MarkAllUnreadFeedItemsAsReadController::class)->name('mark-all-as-read');
    Route::resource('feeds', FeedController::class);

    Route::post('discover-feed', FeedDiscovererController::class)->name('discover-feed');
    Route::post('discover-feed-urls', FeedUrlDiscovererController::class)->name('discover-feed-urls');

    Route::get('feed-items', DashboardController::class);
});

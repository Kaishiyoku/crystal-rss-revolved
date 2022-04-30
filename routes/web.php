<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\ToggleFeedItemReadAtController;
use App\Http\Controllers\UserSettingsController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', WelcomeController::class)->name('welcome');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard/{feed?}/{previousFirstFeedItemChecksum?}/{previousLastFeedItemChecksum?}', DashboardController::class)->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::put('/feeds/mark_all_as_read', [FeedController::class, 'markAllAsRead'])->name('feeds.mark_all_as_read');
    Route::resource('feeds', FeedController::class)->except(['show']);
    Route::put('/feed_items/{feedItem}/read', ToggleFeedItemReadAtController::class)->name('feed_items.toggle_mark_as_read');
    Route::put('/user/settings', UserSettingsController::class)->name('user-edit-settings');
});

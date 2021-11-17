<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentationV1Controller;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\FeedItemController;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/docs/api/v1', [DocumentationV1Controller::class, 'home'])->name('api.v1.home');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::put('/feeds/mark_all_as_read', [FeedController::class, 'markAllAsRead'])->name('feeds.mark_all_as_read');
    Route::resource('feeds', FeedController::class)->except(['show']);
    Route::post('/feed_items', [FeedItemController::class, 'load'])->name('feed_items.load');
    Route::put('/feed_items/{feedItem}/read', [FeedItemController::class, 'toggleMarkAsRead'])->name('feed_items.toggle_mark_as_read');
});

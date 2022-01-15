<?php

use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\FeedController;
use App\Http\Controllers\Api\V1\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->as('api.v1.')->middleware('api')->group(function () {
    Route::get('/health_check', [HomeController::class, 'healthCheck'])->name('health_check');

    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('/user', [HomeController::class, 'user'])->name('user');

        Route::resource('/categories', CategoryController::class)->except(['create', 'edit']);

        Route::put('/feeds/mark_all_as_read', [FeedController::class, 'markAllAsRead'])->name('feeds.mark_all_as_read');
        Route::resource('/feeds', FeedController::class)->except(['create', 'edit']);
    });
});

Route::fallback(function () {
    return response()->json(['message' => 'Not Found.'], 404);
})->name('api.fallback.404');

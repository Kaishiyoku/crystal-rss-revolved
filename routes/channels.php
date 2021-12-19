<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('feed-list.{feedItemUserId}', function ($user, $feedItemUserId) {
    return $user->id === User::findOrNew($feedItemUserId)->id;
}, ['middleware' => ['auth:sanctum', 'verified']]);

Broadcast::channel('test-notification.{userId}', function ($user, $userId) {
    return $user->id === User::findOrNew($userId)->id;
}, ['middleware' => ['auth:sanctum', 'verified']]);

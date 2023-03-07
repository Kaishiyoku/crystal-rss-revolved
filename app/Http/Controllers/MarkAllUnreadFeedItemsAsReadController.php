<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkAllUnreadFeedItemsAsReadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $now = now();

        Auth::user()->feedItems()->unread()->update([
            'read_at' => $now,
        ]);
    }
}

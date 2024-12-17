<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkAllUnreadFeedItemsAsReadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return void
     */
    public function __invoke(Request $request)
    {
        $now = now();

        // @phpstan-ignore-next-line
        Auth::user()->feedItems()->unread()->update([
            'read_at' => $now,
        ]);
    }
}

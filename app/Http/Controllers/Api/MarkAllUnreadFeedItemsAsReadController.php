<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkAllUnreadFeedItemsAsReadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $now = now();

        Auth::user()->feedItems()->unread()->update([
            'read_at' => $now,
        ]);

        return response()->json();
    }
}

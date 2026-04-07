<?php

namespace App\Http\Controllers;

use App\Models\FeedItem;
use Illuminate\Http\JsonResponse;

class MarkFeedItemAsReadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return JsonResponse
     */
    public function __invoke(FeedItem $feedItem)
    {
        $this->authorize('update', $feedItem);

        $feedItem->update([
            'read_at' => now(),
        ]);

        return response()->json($feedItem);
    }
}

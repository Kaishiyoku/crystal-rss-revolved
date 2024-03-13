<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FeedItem;
use Illuminate\Http\JsonResponse;

class ToggleFeedItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(FeedItem $feedItem): JsonResponse
    {
        $this->authorize('update', $feedItem);

        $feedItem->update([
            'read_at' => $feedItem->read_at ? null : now(),
        ]);

        return response()->json($feedItem);
    }
}

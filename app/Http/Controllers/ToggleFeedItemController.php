<?php

namespace App\Http\Controllers;

use App\Models\FeedItem;

class ToggleFeedItemController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(FeedItem $feedItem)
    {
        $this->authorize('update', $feedItem);

        $feedItem->update([
            'read_at' => $feedItem->read_at ? null : now(),
        ]);

        return response()->json($feedItem);
    }
}

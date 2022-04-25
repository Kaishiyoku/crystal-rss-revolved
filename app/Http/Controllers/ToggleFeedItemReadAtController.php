<?php

namespace App\Http\Controllers;

use App\Models\FeedItem;
use Illuminate\Http\Request;

class ToggleFeedItemReadAtController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  FeedItem  $feedItem
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request, FeedItem $feedItem)
    {
        $this->authorize('update', $feedItem);

        $feedItem->update([
            'read_at' => $feedItem->read_at ? null : now(),
        ]);

        return response()->json($feedItem->only(['id', 'read_at']));
    }
}

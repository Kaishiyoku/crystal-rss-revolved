<?php

namespace App\Http\Controllers;

use App\Models\FeedItem;
use Illuminate\Support\Facades\Gate;

class ToggleFeedItemController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\JsonResponse
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

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use App\Models\FeedItem;

class ToggleFeedItemController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(FeedItem $feedItem)
    {
        Gate::authorize('update', $feedItem);

        $feedItem->update([
            'read_at' => $feedItem->read_at ? null : now(),
        ]);

        return response()->json($feedItem);
    }
}

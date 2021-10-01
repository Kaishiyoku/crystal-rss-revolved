<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\FeedItem;
use App\Rules\ArrayOfIntegers;
use Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FeedItemController extends Controller
{
    public function load(Request $request)
    {
        $data = $request->validate([
            'numberOfDisplayedFeedItems' => ['required', 'integer', 'min:0'],
            'offset' => ['integer', 'min:0'],
            'feedItemsPerPage' => ['integer', 'min:0'],
            'readFeedItemIds' => [new ArrayOfIntegers()],
        ]);

        $readFeedItemIds = collect(Arr::get($data, 'readFeedItemIds'));
        $offset = Arr::get($data, 'offset');
        $feedItemsPerPage = Arr::get($data, 'feedItemsPerPage');

        $newUnreadFeedItems = Auth::user()->feedItems()
            ->filteredByFeedItemIds($readFeedItemIds)
            ->paged($feedItemsPerPage, $offset)
            ->get();

        $hasMoreUnreadFeedItems = Auth::user()->feedItems()
                ->filteredByFeedItemIds($readFeedItemIds)
                ->count() > Arr::get($data, 'numberOfDisplayedFeedItems') + $newUnreadFeedItems->count();
        $newOffset = $offset + $feedItemsPerPage;

        return response()->json([
            'newOffset' => $newOffset,
            'newUnreadFeedItems' => $newUnreadFeedItems,
            'hasMoreUnreadFeedItems' => $hasMoreUnreadFeedItems,
        ]);
    }

    public function toggleMarkAsRead(FeedItem $feedItem)
    {
        $this->authorize('update', $feedItem);

        $feedItem->read_at = $feedItem->read_at ? null : now();
        $feedItem->save();

        return response()->json($feedItem->only(['id', 'read_at']));
    }
}

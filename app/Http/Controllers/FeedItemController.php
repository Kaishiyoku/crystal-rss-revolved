<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use App\Models\FeedItem;
use App\Rules\ArrayOfIntegers;
use Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeedItemController extends Controller
{
    public function load(Request $request)
    {
        $data = $request->validate([
            'numberOfDisplayedFeedItems' => ['required', 'integer', 'min:0'],
            'filteredFeedId' => ['nullable', 'integer', Rule::in(Feed::pluck('id'))],
            'offset' => ['integer', 'min:0'],
            'feedItemsPerPage' => ['integer', 'min:0'],
            'readFeedItemIds' => [new ArrayOfIntegers()],
        ]);

        $readFeedItemIds = Arr::get($data, 'readFeedItemIds');
        $filteredFeedId = Arr::get($data, 'filteredFeedId');
        $offset = Arr::get($data, 'offset');
        $feedItemsPerPage = Arr::get($data, 'feedItemsPerPage');

        $newUnreadFeedItems = auth()->user()->feedItems()
            ->unread()
            ->when(count($readFeedItemIds) > 0, function (Builder $query) use ($readFeedItemIds) {
                return $query->orWhereIn('feed_items.id', $readFeedItemIds);
            })
            ->when($filteredFeedId, function (Builder $query) use ($filteredFeedId) {
                return $query->where('feed_id', $filteredFeedId);
            })
            ->with('feed')
            ->orderBy('posted_at', 'desc')
            ->orderBy('feed_items.id', 'desc')
            ->offset($offset)
            ->limit($feedItemsPerPage)
            ->get();

        $hasMoreUnreadFeedItems = auth()->user()->feedItems()->unread()->count() > Arr::get($data, 'numberOfDisplayedFeedItems');
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

        return response()->json($feedItem);
    }
}

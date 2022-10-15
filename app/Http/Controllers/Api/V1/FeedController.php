<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\UpdateFeedRequest;
use App\Models\Category;
use App\Models\Feed;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;

/**
 * @group Feeds
 *
 * API methods for managing feeds
 */
class FeedController extends Controller
{
    /**
     * @var HeraRssCrawler
     */
    private $heraRssCrawler;

    public function __construct()
    {
        $this->heraRssCrawler = new HeraRssCrawler();
    }

    /**
     * Display a listing of the resource.
     *
     * @response scenario=success [{
     *  "id": 1,
     *  "user_id": 1,
     *  "category_id": 1,
     *  "feed_url": "http://www.example.com/feed",
     *  "site_url": "http://www.example.com",
     *  "favicon_url": "http://www.example.com/favicon.ico",
     *  "name": "Example feed",
     *  "last_checked_at": "2022-01-12T21:18:15.000000Z",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "feed_items_count": 7,
     *  "category": {
     *   "id": 1,
     *   "user_id": 1,
     *   "name": "Example category",
     *   "created_at": "2021-09-23T19:11:01.000000Z",
     *   "updated_at": "2021-09-23T19:11:01.000000Z"
     *  }
     * },
     * {
     *  "id": 1,
     *  "user_id": 1,
     *  "category_id": 2,
     *  "feed_url": "http://www.example.com/feed",
     *  "site_url": "http://www.example.com",
     *  "favicon_url": "http://www.example.com/favicon.ico",
     *  "name": "Example feed",
     *  "last_checked_at": "2022-01-12T21:18:15.000000Z",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "feed_items_count": 7,
     *  "category": {
     *   "id": 1,
     *   "user_id": 1,
     *   "name": "Example category",
     *   "created_at": "2021-09-23T19:11:01.000000Z",
     *   "updated_at": "2021-09-23T19:11:01.000000Z"
     *  }
     * }]
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $feeds = Auth::user()->feeds()
            ->withCount('feedItems')
            ->with('category')
            ->orderBy('name')
            ->get();

        return response()->json($feeds);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @response scenario=success {
     *  "id": 1,
     *  "user_id": 1,
     *  "category_id": 1,
     *  "feed_url": "http://www.example.com/feed",
     *  "site_url": "http://www.example.com",
     *  "favicon_url": "http://www.example.com/favicon.ico",
     *  "name": "Example feed",
     *  "last_checked_at": "2022-01-12T21:18:15.000000Z",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "feed_items_count": 7,
     *  "category": {
     *   "id": 1,
     *   "user_id": 1,
     *   "name": "Example category",
     *   "created_at": "2021-09-23T19:11:01.000000Z",
     *   "updated_at": "2021-09-23T19:11:01.000000Z"
     *  }
     * }
     *
     * @param  \App\Http\Requests\StoreFeedRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreFeedRequest $request)
    {
        $validated = $request->validated();

        $faviconUrl = $this->heraRssCrawler->discoverFavicon(Arr::get($validated, 'site_url'));

        $feed = new Feed(Arr::add($validated, 'favicon_url', $faviconUrl));

        Auth::user()->feeds()->save($feed);

        return response()->json($feed);
    }

    /**
     * Display the specified resource.
     *
     * @response scenario=success {
     *  "id": 1,
     *  "user_id": 1,
     *  "category_id": 1,
     *  "feed_url": "http://www.example.com/feed",
     *  "site_url": "http://www.example.com",
     *  "favicon_url": "http://www.example.com/favicon.ico",
     *  "name": "Example feed",
     *  "last_checked_at": "2022-01-12T21:18:15.000000Z",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "feed_items_count": 7,
     *  "category": {
     *   "id": 1,
     *   "user_id": 1,
     *   "name": "Example category",
     *   "created_at": "2021-09-23T19:11:01.000000Z",
     *   "updated_at": "2021-09-23T19:11:01.000000Z"
     *  }
     * }
     *
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Feed $feed)
    {
        $this->authorize('view', $feed);

        return response()->json($feed);
    }

    /**
     * Update the specified resource in storage.
     *
     * @response scenario=success [{
     *  "id": 1,
     *  "user_id": 1,
     *  "category_id": 1,
     *  "feed_url": "http://www.example.com/feed",
     *  "site_url": "http://www.example.com",
     *  "favicon_url": "http://www.example.com/favicon.ico",
     *  "name": "Example feed",
     *  "last_checked_at": "2022-01-12T21:18:15.000000Z",
     *  "created_at": "2021-07-19T16:46:51.000000Z",
     *  "updated_at": "2021-08-22T12:26:11.000000Z",
     *  "feed_items_count": 7,
     *  "category": {
     *   "id": 1,
     *   "user_id": 1,
     *   "name": "Example category",
     *   "created_at": "2021-09-23T19:11:01.000000Z",
     *   "updated_at": "2021-09-23T19:11:01.000000Z"
     *  }
     * }
     *
     * @param  \App\Http\Requests\UpdateFeedRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateFeedRequest $request, Feed $feed)
    {
        $feed->update($request->validated());

        return response()->json($feed);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @response scenario=success {}
     *
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Feed $feed)
    {
        $this->authorize('delete', $feed);

        $feed->feedItems()->delete();
        $feed->delete();

        return response()->json();
    }

    /**
     * Mark all unread feed items as read.
     *
     * @response scenario=success {}
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        $this->authorize('mark-all-as-read');

        $now = now();

        Auth::user()->feeds()->with('unreadFeedItems')->get()->each(function (Feed $feed) use ($now) {
            $feed->feedItems()->update(['read_at' => $now]);
        });

        return response()->json();
    }
}

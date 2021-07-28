<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\UpdateFeedRequest;
use App\Models\Category;
use App\Models\Feed;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Kaishiyoku\HeraRssCrawler\HeraRssCrawler;

class FeedController extends Controller
{
    /**
     * @var HeraRssCrawler
     */
    private $heraRssCrawler;

    /**
     * FeedController constructor.
     */
    public function __construct()
    {
        $this->heraRssCrawler = new HeraRssCrawler();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $feeds = Auth::user()->feeds()->with('category')->orderBy('name')->get();

        return view('feed.index', [
            'feeds' => $feeds,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $availableCategoryOptions = Category::getAvailableOptions();

        $feed = Feed::make();

        return view('feed.create', [
            'feed' => $feed,
            'availableCategoryOptions' => $availableCategoryOptions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFeedRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreFeedRequest $request)
    {
        $validated = $request->validated();

        $faviconUrl = $this->heraRssCrawler->discoverFavicon(Arr::get($validated, 'site_url'));

        $feed = Feed::make(Arr::add($validated, 'favicon_url', $faviconUrl));
        Auth::user()->feeds()->save($feed);

        return redirect()->route('feeds.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Feed $feed)
    {
        $this->authorize('update', $feed);

        return view('feed.edit', [
            'feed' => $feed,
            'availableCategoryOptions' => Category::getAvailableOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFeedRequest  $request
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateFeedRequest $request, Feed $feed)
    {
        $validated = $request->validated();

        $faviconUrl = $this->heraRssCrawler->discoverFavicon(Arr::get($validated, 'site_url'));

        $feed->update(Arr::add($validated, 'favicon_url', $faviconUrl));

        return redirect()->route('feeds.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Feed $feed)
    {
        $this->authorize('delete', $feed);

        $feed->feedItems()->delete();
        $feed->delete();

        return redirect()->route('feeds.index');
    }

    /**
     * Mark all unread feed items as read.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $now = now();

        Auth::user()->feeds()->with('unreadFeedItems')->get()->each(function (Feed $feed) use ($now) {
            $feed->feedItems()->update(['read_at' => $now]);
        });

        return redirect()->route('dashboard');
    }
}

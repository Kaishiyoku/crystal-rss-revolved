<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feed;
use App\Rules\ValidFeedUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', Rule::in(Category::getAvailableOptions()->keys())],
            'feed_url' => ['required', 'url', new ValidFeedUrl()],
            'site_url' => ['required', 'url'],
            'name' => ['required', Rule::unique('feeds', 'name')->where('user_id', Auth::user()->id)],
        ]);

        $faviconUrl = $this->heraRssCrawler->discoverFavicon(Arr::get($data, 'site_url'));

        $feed = Feed::make(Arr::add($data, 'favicon_url', $faviconUrl));
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Feed $feed)
    {
        $this->authorize('update', $feed);

        $data = $request->validate([
            'category_id' => ['required', Rule::in(Category::getAvailableOptions()->keys())],
            'feed_url' => ['required', 'url', new ValidFeedUrl()],
            'site_url' => ['required', 'url'],
            'name' => ['required', Rule::unique('feeds', 'name')->where('user_id', Auth::user()->id)->ignore($feed)],
        ]);

        $faviconUrl = $this->heraRssCrawler->discoverFavicon(Arr::get($data, 'site_url'));

        $feed->update(Arr::add($data, 'favicon_url', $faviconUrl));

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
     * @param  \App\Models\Feed  $feed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAllAsRead()
    {
        $now = now();

        Auth::feeds()->with('unreadFeedItems')->get()->each(function (Feed $feed) use ($now) {
            $feed->feedItems()->update(['read_at' => $now]);
        });

        return redirect()->route('dashboard');
    }
}

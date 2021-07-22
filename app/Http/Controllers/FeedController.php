<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feed;
use App\Rules\ValidFeedUrl;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FeedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $feeds = auth()->user()->feeds;

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
            'name' => ['required', Rule::unique('feeds', 'name')->where('user_id', auth()->user()->id)],
        ]);

        $feed = Feed::make($data);
        auth()->user()->feeds()->save($feed);

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
        $this->authorize('view', $feed);

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
            'name' => ['required', Rule::unique('feeds', 'name')->where('user_id', auth()->user()->id)->ignore($feed)],
        ]);

        $feed->update($data);

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
}

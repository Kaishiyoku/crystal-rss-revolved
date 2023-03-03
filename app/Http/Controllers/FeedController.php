<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\UpdateFeedRequest;
use App\Models\Feed;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Feed::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Feeds/Index', [
            'feeds' => Auth::user()->feeds,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Feeds/Create', [
            'categories' => Auth::user()->categories()->pluck('name', 'id')->map(fn(string $name, int $id) => ['value' => $id, 'name' => $name])->values(),
            'feed' => new Feed(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeedRequest $request)
    {
        $validated = $request->validated();

        $feed = new Feed($validated);
        $feed->category_id = Arr::get($validated, 'category_id');

        Auth::user()->feeds()->save($feed);

        return redirect()->route('feeds.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feed $feed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeedRequest $request, Feed $feed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feed $feed)
    {
        //
    }
}

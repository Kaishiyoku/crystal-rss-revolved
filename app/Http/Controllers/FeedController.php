<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\UpdateFeedRequest;
use App\Models\Feed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:viewAny,App\Models\Feed')->only('index');
        $this->middleware('can:view,feed')->only('show');
        $this->middleware('can:create,App\Models\Feed')->only('create', 'store');
        $this->middleware('can:update,feed')->only('edit', 'update');
        $this->middleware('can:delete,feed')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Feeds/Index', [
            'feeds' => Auth::user()->feeds()->with('category')->withCount('feedItems')->get(),
            'canCreate' => Auth::user()->can('create', Feed::class),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Feeds/Create', [
            'categories' => Auth::user()->categories()->pluck('name', 'id')->map(fn (string $name, int $id) => ['value' => $id, 'name' => $name])->values(),
            'feed' => new Feed,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeedRequest $request): RedirectResponse
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
    public function edit(Feed $feed): Response
    {
        return Inertia::render('Feeds/Edit', [
            'categories' => Auth::user()->categories()->pluck('name', 'id')->map(fn (string $name, int $id) => ['value' => $id, 'name' => $name])->values(),
            'feed' => $feed,
            'canDelete' => Auth::user()->can('delete', $feed),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeedRequest $request, Feed $feed): RedirectResponse
    {
        $validated = $request->validated();

        $feed = $feed->fill($validated);
        $feed->category_id = Arr::get($validated, 'category_id');

        $feed->save();

        return redirect()->route('feeds.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feed $feed): RedirectResponse
    {
        $feed->feedItems()->delete();
        $feed->delete();

        return redirect()->route('feeds.index');
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFeedRequest;
use App\Http\Requests\UpdateFeedRequest;
use App\Models\Feed;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Feed::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'feeds' => Auth::user()->feeds()->with('category')->withCount('feedItems')->get(),
            'canCreate' => Auth::user()->can('create', Feed::class),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        return response()->json([
            'categories' => $this->categories(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeedRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $feed = new Feed($validated);
        $feed->category_id = Arr::get($validated, 'category_id');

        Auth::user()->feeds()->save($feed);

        return response()->json();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feed $feed): JsonResponse
    {
        return response()->json([
            'categories' => $this->categories(),
            'feed' => $feed,
            'canDelete' => Auth::user()->can('delete', $feed),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeedRequest $request, Feed $feed): JsonResponse
    {
        $validated = $request->validated();

        $feed = $feed->fill($validated);
        $feed->category_id = Arr::get($validated, 'category_id');

        $feed->save();

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feed $feed): JsonResponse
    {
        $feed->feedItems()->delete();
        $feed->delete();

        return response()->json();
    }

    /**
     * @return Collection<int, array<{value: int, name: string}>>
     */
    private function categories(): Collection
    {
        return Auth::user()->categories()->pluck('name', 'id')->map(fn (string $name, int $id) => ['value' => $id, 'name' => $name])->values();
    }
}

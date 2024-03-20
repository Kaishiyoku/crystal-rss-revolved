<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DashboardRequest $request): ResponseFactory|JsonResponse
    {
        $feedId = $request->exists('feed_id') ? $request->integer('feed_id') : null;

        $totalNumberOfFeedItems = Auth::user()->feedItems()->unread()->count();
        $unreadFeeds = Auth::user()->feeds()
            ->select(['id', 'name'])
            ->whereHas('feedItems', fn (Builder $query) => $query->unread()) /** @phpstan-ignore-line */
            ->withCount(['feedItems' => fn (Builder $query) => $query->unread()]) /** @phpstan-ignore-line */
            ->get();

        $feedItems = Auth::user()->feedItems()
            ->unread()
            ->when($feedId, fn (Builder $query) => $query->where('feed_id', $feedId)) /** @phpstan-ignore-line */
            ->with('feed')
            ->cursorPaginate()
            ->withQueryString();

        return response()->json([
            'selectedFeed' => $feedId ? $unreadFeeds->firstWhere('id', $feedId) : null,
            'totalNumberOfFeedItems' => $totalNumberOfFeedItems,
            'unreadFeeds' => $unreadFeeds,
            'feedItems' => $feedItems,
            'currentCursor' => $request->query('cursor'),
        ]);
    }
}

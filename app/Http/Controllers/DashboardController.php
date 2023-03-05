<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use App\Models\FeedItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DashboardRequest $request)
    {
        $feedId = $request->exists('feed_id') ? $request->integer('feed_id') : null;

        $totalNumberOfFeedItems = Auth::user()->feedItems()->unread()->count();
        $unreadFeeds = Auth::user()->feeds()
            ->select(['id', 'name'])
            ->whereHas('feedItems', function (Builder $query) {
                $query->unread();
            })
            ->withCount(['feedItems' => function (Builder $query) {
                $query->unread();
            }])
            ->get();

        $feedItems = Auth::user()->feedItems()
            ->unread()
            ->when($feedId, fn(Builder $query) => $query->where('feed_id', $feedId))
            ->with('feed')
            ->cursorPaginate()
            ->withQueryString();

        // if there are no unread feed items go back to dashboard without query strings
        if (!$feedItems->hasPages()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Dashboard', [
            'totalNumberOfFeedItems' => $totalNumberOfFeedItems,
            'unreadFeeds' => $unreadFeeds,
            'feedItems' => $feedItems,
            'currentCursor' => $request->query('cursor'),
        ]);
    }
}

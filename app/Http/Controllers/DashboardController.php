<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
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
        $selectedFeedId = $request->exists('feed_id') ? $request->integer('feed_id') : null;

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
            ->when($selectedFeedId, fn(Builder $query) => $query->where('feed_id', $selectedFeedId))
            ->with('feed')
            ->cursorPaginate()
            ->withQueryString();

        // if feed filtering is active and there are no unread feed items go back to dashboard without query strings
        if ($selectedFeedId && $feedItems->isEmpty()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Dashboard', [
            'selectedFeedId' => $selectedFeedId,
            'totalNumberOfFeedItems' => $totalNumberOfFeedItems,
            'unreadFeeds' => $unreadFeeds,
            'feedItems' => $feedItems,
            'currentCursor' => $request->query('cursor'),
        ]);
    }
}

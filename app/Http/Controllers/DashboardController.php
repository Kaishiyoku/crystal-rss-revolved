<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(DashboardRequest $request): RedirectResponse|Response
    {
        $feedId = $request->exists('feed_id') ? $request->integer('feed_id') : null;

        $totalNumberOfFeedItems = Auth::user()->feedItems()->unread()->count(); // @phpstan-ignore-line

        // @phpstan-ignore-next-line
        $feedItems = Auth::user()->feedItems()
            ->unread()
            ->when($feedId, fn (Builder $query) => $query->where('feed_id', $feedId))
            ->with('feed')
            ->cursorPaginate()
            ->withQueryString();

        // if feed filtering is active and there are no unread feed items go back to dashboard without query strings
        if ($feedId && $feedItems->isEmpty()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('Dashboard', [
            'totalNumberOfFeedItems' => $totalNumberOfFeedItems,
            'feedItems' => $feedItems,
            'currentCursor' => $request->query('cursor'),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $totalNumberOfFeedItems = Auth::user()->feedItems()->unread()->count();
        $feedItems = Auth::user()->feedItems()->unread()->with('feed')->cursorPaginate();

        return Inertia::render('Dashboard', [
            'totalNumberOfFeedItems' => $totalNumberOfFeedItems,
            'feedItems' => $feedItems,
        ]);
    }
}

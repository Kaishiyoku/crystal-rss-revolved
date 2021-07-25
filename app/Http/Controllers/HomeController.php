<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function welcome()
    {
        return view('welcome');
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function dashboard()
    {
        $totalUnreadFeedItems = auth()->user()->feedItems()->unread()->count();

        return view('dashboard', [
            'totalUnreadFeedItems' => $totalUnreadFeedItems,
        ]);
    }

}

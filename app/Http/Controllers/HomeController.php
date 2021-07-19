<?php

namespace App\Http\Controllers;

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
        $unreadFeedItems = auth()->user()->feedItems()->unread()->with('feed')->take(20)->get();

        return view('dashboard', [
            'unreadFeedItems' => $unreadFeedItems,
        ]);
    }

}

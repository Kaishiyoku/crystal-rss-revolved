<?php

namespace App\Http\Controllers;

use App\Events\TestNotificationSent;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function welcome()
    {
        return view('welcome');
    }

    public function sendTestNotification()
    {
        broadcast(new TestNotificationSent(Auth::id()));
    }
}

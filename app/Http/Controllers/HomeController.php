<?php

namespace App\Http\Controllers;

use App\Models\Feed;
use Illuminate\Pagination\CursorPaginator;

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
        return view('dashboard');
    }

}

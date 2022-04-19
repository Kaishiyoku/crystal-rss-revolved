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
}

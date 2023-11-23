<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class HandleLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Str::substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        App::setLocale($locale);
        setlocale(LC_TIME, $locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}

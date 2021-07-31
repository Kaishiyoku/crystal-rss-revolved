<?php

namespace App\Http\Middleware;

use Cache;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = Str::substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

        $cachedLocale = Cache::get('locale');

        if ($cachedLocale !== $locale) {
            Cache::set('locale', $locale);

            App::setLocale($locale);
        } else {
            App::setLocale($locale);
        }

        return $next($request);
    }
}

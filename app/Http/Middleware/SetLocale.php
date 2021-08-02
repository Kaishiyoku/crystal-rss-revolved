<?php

namespace App\Http\Middleware;

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
        $httpLocale = Str::substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        $locale = collect(config('app.available_locales'))->contains($httpLocale) ? $httpLocale : config('app.fallback_locale');

        App::setLocale($locale);

        return $next($request);
    }
}

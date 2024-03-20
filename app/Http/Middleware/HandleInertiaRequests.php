<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app_inertia';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'locale' => $request->getLocale(),
            'localeValues' => collect($this->availableLocales())->map(fn (string $locale) => ['label' => __('common.locale.'.$locale), 'value' => $locale]),
            'ziggy' => function () use ($request) {
                return array_merge((new Ziggy)->toArray(), [
                    'location' => $request->url(),
                ]);
            },
            'monthsAfterPruningFeedItems' => fn () => $request->user() ? config('app.months_after_pruning_feed_items') : null,
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function availableLocales(): array
    {
        return config('app.available_locales');
    }
}

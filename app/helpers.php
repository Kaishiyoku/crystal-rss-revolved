<?php

use Illuminate\Support\Collection;
use Spatie\Color\Rgb;

if (!function_exists('getContentTypeForUrl')) {
    function getContentTypeForUrl(string $url): ?string
    {
        try {
            $headers = get_headers($url, true);

            $contentType = Arr::get($headers, 'Content-Type');

            if (is_array($contentType)) {
                return null;
            }

            return $contentType;
        } catch (Exception $e) {
            return null;
        }
    }
}

if (!function_exists('rgbToString')) {
    function rgbToString(Rgb $rgb, string $glue = ', '): string
    {
        return collect([
            $rgb->red(),
            $rgb->green(),
            $rgb->blue(),
        ])->join($glue);
    }
}

if (!function_exists('availableThemeColorFields')) {
    function availableThemeColorFields(): Collection
    {
        $colorVariations = collect([
            50,
            100,
            200,
            300,
            400,
            500,
            600,
            700,
            800,
            900,
        ]);

        $colorGroups = collect([
            'primary',
            'secondary',
            'gray',
        ]);

        return $colorGroups->map(
            fn(string $colorGroup) => $colorVariations->map(
                fn(int $variation) => "color_{$colorGroup}_{$variation}"
            )
        )->flatten();
    }
}

if (!function_exists('plainRgbToHex')) {
    function plainRgbToHex(string $rgb): string
    {
        return Rgb::fromString("rgb({$rgb})")->toHex();
    }
}

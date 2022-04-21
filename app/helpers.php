<?php

use App\Enums\ColorTheme;

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

if (!function_exists('getTranslationForColorTheme')) {
    function getTranslationForColorTheme(ColorTheme $colorTheme): string
    {
        return Str::of($colorTheme->key)->headline()->toString();
    }
}

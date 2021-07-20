<?php

if (!function_exists('isImageUrl')) {
    function isImageUrl(string $url): bool
    {
        $headers = get_headers($url, true);

        return Str::startsWith('image/', Arr::get($headers, 'Content-Type'));
    }
}

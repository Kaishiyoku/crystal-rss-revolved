<?php

if (!function_exists('isImageUrl')) {
    function isImageUrl(string $url): bool
    {
        try {
            $headers = get_headers($url, true);

            return Str::startsWith(Arr::get($headers, 'Content-Type'), 'image/');
        } catch (Exception $e) {
            return false;
        }
    }
}

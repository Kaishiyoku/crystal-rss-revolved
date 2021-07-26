<?php

if (!function_exists('getContentTypeForUrl')) {
    function getContentTypeForUrl(string $url): ?string
    {
        try {
            $headers = get_headers($url, true);

            return Arr::get($headers, 'Content-Type');
        } catch (Exception $e) {
            return null;
        }
    }
}

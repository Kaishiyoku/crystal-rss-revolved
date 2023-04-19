<?php

declare(strict_types=1);

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

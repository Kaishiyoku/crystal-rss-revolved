<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Header;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

if (! function_exists('getContentTypeForUrl')) {
    function getContentTypeForUrl(string $url): ?string
    {
        try {
            $contentTypeHeaders = Arr::flatten(Header::parse(Http::get($url)->header('Content-Type')));

            return Arr::get($contentTypeHeaders, 0);
        } catch (Exception $e) {
            return null;
        }
    }
}

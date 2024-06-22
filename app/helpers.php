<?php

declare(strict_types=1);

use GuzzleHttp\Psr7\Header;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Colors\Rgb\Channels\Blue;
use Intervention\Image\Colors\Rgb\Channels\Green;
use Intervention\Image\Colors\Rgb\Channels\Red;
use Intervention\Image\Colors\Rgb\Colorspace as RgbColorspace;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;
use kornrunner\Blurhash\Blurhash;

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

if (! function_exists('generateBlurHashByUrl')) {
    function generateBlurHashByUrl(string $imageUrl): ?string
    {
        try {
            $imageManager = new ImageManager(new GdDriver());
            $image = $imageManager->read(Http::get($imageUrl)->body());
            $image->scaleDown(100);

            $pixels = [];

            for ($y = 0; $y < $image->height(); $y++) {
                $row = [];

                for ($x = 0; $x < $image->width(); $x++) {
                    $colors = $image->pickColor($x, $y)->convertTo(new RgbColorspace());

                    $row[] = [
                        $colors->channel(Red::class)->value(),
                        $colors->channel(Green::class)->value(),
                        $colors->channel(Blue::class)->value(),
                    ];
                }
                $pixels[] = $row;
            }

            return Blurhash::encode($pixels, 4, 3);
        } catch (Exception) {
            Log::warning("Couldn't generate blur hash for image {$imageUrl}");

            return null;
        }
    }
}

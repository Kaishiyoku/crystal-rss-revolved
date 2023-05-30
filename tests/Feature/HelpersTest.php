<?php

namespace Tests\Feature;

it('should get the proper content type for a given url', function () {
    $urls = collect([
        'https://google.com',
        'https://petapixel.com/assets/uploads/2022/10/Instagram-is-Testing-the-Ability-to-Add-Songs-to-Profiles-1600x840.jpg',
        'https://petapixel.com/assets/uploads/2023/04/OM-Digital-Has-Turned-its-Cameras-into-Powerful-3D-Scanners-1600x840.jpg',
        'https://laravelnews.s3.amazonaws.com/images/laravel-10-featured.png',
    ]);

    expect($urls->map(fn (string $url) => getContentTypeForUrl($url))->toArray())->toBe([
        'text/html',
        'image/jpeg',
        'image/jpeg',
        'image/png',
    ]);
});

it('should return null if an exception occurs', function () {
    expect(getContentTypeForUrl('https://nonexistent-url.dev'))->toBeNull();
});

<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

it('converts a feed item to a PDF', function () {
    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    $sampleResponseBody = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Test</title>
    <link rel="stylesheet" href="css/main.css" />
    <link rel="icon" href="images/favicon.png" />
  </head>

  <body>
    <header>
        Header content
    </header>

    <main>
        <p>Test content.</p>
    </main>
  </body>
</html>
HTML;

    Http::fake(fn () => Http::response($sampleResponseBody));

    get(route('feed-item-pdf', $feedItem))
        ->assertOk();
});

it('does not allow to generate a PDF of a feed item owned by another user', function () {
    actingAs(User::factory()->create());

    $anotherUser = User::factory()->create();

    $feed = Feed::factory()->for($anotherUser)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    get(route('feed-item-pdf', $feedItem))
        ->assertForbidden();
});

it('shows a 404 error when the PDF parser fails', function () {
    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->for($user)->create();
    $feedItem = FeedItem::factory()->for($feed)->create();

    Http::fake(fn () => Http::response('<p>Invalid HTML body.</p>'));

    get(route('feed-item-pdf', $feedItem))
        ->assertNotFound();
});

it('cannot be accessed as guest', function () {
    $feedItem = FeedItem::factory()->create();

    get(route('feed-item-pdf', $feedItem))
        ->assertRedirect('/login');
});

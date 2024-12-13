<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\freezeTime;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

test('marks all unread feed items as read for specific user', function () {
    freezeTime();

    actingAs($user = User::factory()->create());

    Feed::factory(5)
        ->recycle($user)
        ->has(FeedItem::factory(10)->state(['read_at' => null]))
        ->has(FeedItem::factory(20)->state(['read_at' => now()]))
        ->create();

    expect($user->feedItems()->unread()->count())->toBe(50)
        ->and($user->feedItems()->whereNotNull('read_at')->count())->toBe(100);

    put(route('mark-all-as-read'))
        ->assertOk();

    expect($user->feedItems()->unread()->count())->toBe(0)
        ->and($user->feedItems()->whereNotNull('read_at')->count())->toBe(150);
});

test('cannot access as guest', function () {
    put(route('mark-all-as-read'))
        ->assertRedirect('/login');
});

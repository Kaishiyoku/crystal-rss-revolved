<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('marks all unread feed items as read for specific user', function () {
    $this->freezeTime();

    $this->actingAs($user = User::factory()->create());

    Feed::factory(5)
        ->recycle($user)
        ->has(FeedItem::factory(10)->state(['read_at' => null]))
        ->has(FeedItem::factory(20)->state(['read_at' => now()]))
        ->create();

    static::assertSame(50, $user->feedItems()->unread()->count());
    static::assertSame(100, $user->feedItems()->whereNotNull('read_at')->count());

    $this->put(route('mark-all-as-read'))
        ->assertOk();

    static::assertSame(0, $user->feedItems()->unread()->count());
    static::assertSame(150, $user->feedItems()->whereNotNull('read_at')->count());
});

test('cannot access as guest', function () {
    $this->put(route('mark-all-as-read'))
        ->assertRedirect('/login');
});

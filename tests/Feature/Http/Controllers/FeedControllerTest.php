<?php

use App\Http\Controllers\FeedController;
use App\Models\Category;
use App\Models\Feed;
use App\Models\FeedItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia as Assert;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\delete;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

test('middleware is registered', function () {
    $middleware = collect((new FeedController)->getMiddleware())
        ->map(fn (array $arr) => Arr::get($arr, 'middleware'))
        ->toArray();

    $expectedMiddleware = [
        'can:viewAny,App\Models\Feed',
        'can:view,feed',
        'can:create,App\Models\Feed',
        'can:update,feed',
        'can:delete,feed',
    ];

    expect($middleware)->toHaveCount(5)
        ->and($middleware)->toBe($expectedMiddleware);
});

test('index', function () {
    actingAs($user = User::factory()->create());

    Feed::factory()->for($user)->create();

    get(route('feeds.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('Feeds/Index')
            ->count('feeds', 1)
            ->where('canCreate', true)
        );
});

test('cannot access index as guest', function () {
    get(route('feeds.index'))
        ->assertRedirect('/login');
});

test('create', function () {
    actingAs(User::factory()->create());

    get(route('feeds.create'))
        ->assertInertia(fn (Assert $page) => $page);
});

test('store', function () {
    actingAs($user = User::factory()->create());

    $category = Category::factory()->for($user)->create();
    $feedUrl = 'https://tailwindcss.com/feeds/feed.xml';
    $siteUrl = 'https://tailwindcss.com/blog';
    $faviconUrl = 'https://tailwindcss.com/blog/favicons/apple-touch-icon.png?v=3';
    $name = 'Tailwind CSS Blog';
    $language = 'en';
    $isPurgeable = true;
    $isPdfExportEnabled = true;

    post(route('feeds.store'), [
        'category_id' => $category->id,
        'feed_url' => $feedUrl,
        'site_url' => $siteUrl,
        'favicon_url' => $faviconUrl,
        'name' => $name,
        'language' => $language,
        'is_purgeable' => $isPurgeable,
        'is_pdf_export_enabled' => $isPdfExportEnabled,
    ])
        ->assertRedirect(route('feeds.index'));

    expect($user->feeds()->count())->toBe(1)
        ->and($user->feeds()->first()->user_id)->toBe($user->id)
        ->and($user->feeds()->first()->category_id)->toBe($category->id)
        ->and($user->feeds()->first()->feed_url)->toBe($feedUrl)
        ->and($user->feeds()->first()->site_url)->toBe($siteUrl)
        ->and($user->feeds()->first()->favicon_url)->toBe($faviconUrl)
        ->and($user->feeds()->first()->name)->toBe($name)
        ->and($user->feeds()->first()->language)->toBe($language)
        ->and($user->feeds()->first()->is_purgeable)->toBe($isPurgeable)
        ->and($user->feeds()->first()->is_pdf_export_enabled)->toBe($isPdfExportEnabled);
});

test('store validation fails due to missing data', function () {
    actingAs($user = User::factory()->create());

    get(route('feeds.create'));
    $response = post(route('feeds.store'), ['name' => ' ']);

    $response->assertRedirect(route('feeds.create'));
    $response->assertSessionHasErrors(['name' => 'The Name field is required.']);
    expect($user->feeds()->count())->toBe(0);
});

test('edit', function () {
    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->for($user)->create();

    get(route('feeds.edit', $feed))
        ->assertInertia(fn (Assert $page) => $page
            ->has('feed')
            ->where('canDelete', true)
        );
});

test('cannot edit feed of another user', function () {
    actingAs(User::factory()->create());

    $feed = Feed::factory()->create();

    get(route('feeds.edit', $feed))
        ->assertForbidden();
});

test('update', function () {
    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->for($user)->create();

    $category = Category::factory()->for($user)->create();
    $feedUrl = 'https://tailwindcss.com/feeds/feed.xml/?updated';
    $siteUrl = 'https://tailwindcss.com/blog/?updated';
    $faviconUrl = 'https://tailwindcss.com/blog/favicons/apple-touch-icon.png?v=3&updated';
    $name = 'Tailwind CSS Blog (Updated)';
    $language = 'de';
    $isPurgeable = false;
    $isPdfExportEnabled = false;

    $response = put(route('feeds.update', $feed), [
        'category_id' => $category->id,
        'feed_url' => $feedUrl,
        'site_url' => $siteUrl,
        'favicon_url' => $faviconUrl,
        'name' => $name,
        'language' => $language,
        'is_purgeable' => $isPurgeable,
        'is_pdf_export_enabled' => $isPdfExportEnabled,
    ]);

    $response->assertRedirect(route('feeds.index'));
    expect($user->feeds()->first()->user_id)->toBe($user->id)
        ->and($user->feeds()->first()->category_id)->toBe($category->id)
        ->and($user->feeds()->first()->feed_url)->toBe($feedUrl)
        ->and($user->feeds()->first()->site_url)->toBe($siteUrl)
        ->and($user->feeds()->first()->favicon_url)->toBe($faviconUrl)
        ->and($user->feeds()->first()->name)->toBe($name)
        ->and($user->feeds()->first()->language)->toBe($language)
        ->and($user->feeds()->first()->is_purgeable)->toBe($isPurgeable)
        ->and($user->feeds()->first()->is_pdf_export_enabled)->toBe($isPdfExportEnabled);
});

test('cannot update feed of another user', function () {
    actingAs(User::factory()->create());

    $feed = Feed::factory()->create();

    put(route('feeds.update', $feed), ['name' => 'Test (updated)'])
        ->assertForbidden();
});

test('delete', function () {
    actingAs($user = User::factory()->create());

    $feed = Feed::factory()->for($user)->hasFeedItems(10)->create();

    $response = delete(route('feeds.destroy', $feed));

    $response->assertRedirect(route('feeds.index'));
    expect($user->feeds()->count())->toBe(0)
        ->and($user->feedItems()->count())->toBe(0);
});

test('cannot delete feed of another user', function () {
    actingAs(User::factory()->create());

    $feed = Feed::factory()->hasFeedItems(10)->create();

    delete(route('feeds.destroy', $feed))
        ->assertForbidden();

    expect(Feed::count())->toBe(1)
        ->and(FeedItem::count())->toBe(10);
});

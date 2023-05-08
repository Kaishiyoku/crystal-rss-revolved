<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * App\Models\FeedItem
 *
 * @property int $id
 * @property int $feed_id
 * @property string $checksum
 * @property string $url
 * @property string $title
 * @property string|null $image_url
 * @property string|null $image_mimetype
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $posted_at
 * @property \Illuminate\Support\Carbon|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Feed $feed
 * @method static \Database\Factories\FeedItemFactory factory($count = null, $state = [])
 * @method static Builder|FeedItem newModelQuery()
 * @method static Builder|FeedItem newQuery()
 * @method static Builder|FeedItem ofFeed(?int $feedId)
 * @method static Builder|FeedItem query()
 * @method static Builder|FeedItem unread()
 * @method static Builder|FeedItem whereChecksum($value)
 * @method static Builder|FeedItem whereCreatedAt($value)
 * @method static Builder|FeedItem whereDescription($value)
 * @method static Builder|FeedItem whereFeedId($value)
 * @method static Builder|FeedItem whereId($value)
 * @method static Builder|FeedItem whereImageMimetype($value)
 * @method static Builder|FeedItem whereImageUrl($value)
 * @method static Builder|FeedItem wherePostedAt($value)
 * @method static Builder|FeedItem whereReadAt($value)
 * @method static Builder|FeedItem whereTitle($value)
 * @method static Builder|FeedItem whereUpdatedAt($value)
 * @method static Builder|FeedItem whereUrl($value)
 * @mixin \Eloquent
 */
class FeedItem extends Model
{
    use HasFactory, MassPrunable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'checksum',
        'url',
        'title',
        'image_url',
        'image_mimetype',
        'description',
        'posted_at',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'posted_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<string>
     */
    protected $appends = [
        'has_image',
    ];

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage()
    {
        return config('app.feed_items_per_page');
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('read_at', '<=', now()->subMonths(config('app.months_after_pruning_feed_items')));
    }

    /**
     * Does the feed item has an image?
     */
    protected function hasImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_url && Str::startsWith($this->image_mimetype, 'image/'),
        );
    }

    /**
     * Scope a query to only include unread feed items.
     */
    public function scopeUnread(Builder $query): void
    {
        $query->whereNull('read_at');
    }

    /**
     * Scope a query to only include feed items of a given feed if the feed ID is not null.
     */
    public function scopeOfFeed(Builder $query, ?int $feedId): void
    {
        $query->when($feedId, fn($query) => $query->where('feed_id', $feedId));
    }

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
    }
}

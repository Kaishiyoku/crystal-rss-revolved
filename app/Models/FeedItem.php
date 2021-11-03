<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * App\Models\FeedItem
 *
 * @property int $id
 * @property int $feed_id
 * @property string $url
 * @property string $title
 * @property string|null $image_url
 * @property string|null $image_mimetype
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $posted_at
 * @property string $checksum
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Feed $feed
 * @property-read mixed $formatted_posted_at
 * @property-read mixed $has_image
 * @method static Builder|FeedItem filteredByFeedItemIds(\Illuminate\Support\Collection $readFeedItemIds)
 * @method static Builder|FeedItem newModelQuery()
 * @method static Builder|FeedItem newQuery()
 * @method static Builder|FeedItem paged($limit, $offset)
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
     * @var array
     */
    protected $fillable = [
        'url',
        'title',
        'image_url',
        'image_mimetype',
        'description',
        'posted_at',
        'checksum',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'posted_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_posted_at',
        'has_image',
    ];

    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        return static::where('read_at', true)
            ->where('posted_at', '<=', now()->subMonths(config('app.months_after_pruning_feed_items')));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeFilteredByFeedItemIds($query, Collection $readFeedItemIds)
    {
        return $query
            ->unread()
            ->when($readFeedItemIds->isNotEmpty(), fn($query) => $query->orWhereIn('feed_items.id', $readFeedItemIds))
            ->with('feed')
            ->orderBy('posted_at', 'desc')
            ->orderBy('feed_items.id', 'desc');
    }

    public function scopePaged($query, $limit, $offset)
    {
        return $query
            ->offset($offset)
            ->limit($limit);
    }

    /**
     * @return bool
     */
    public function hasImage()
    {
        return $this->image_url && Str::startsWith($this->image_mimetype, 'image/');
    }

    public function getFormattedPostedAtAttribute()
    {
        return $this->posted_at->format(__('date.datetime'));
    }

    public function getHasImageAttribute()
    {
        return $this->hasImage();
    }
}

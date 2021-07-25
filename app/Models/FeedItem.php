<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FeedItem
 *
 * @property int $id
 * @property int $user_id
 * @property int $feed_id
 * @property string $url
 * @property string $title
 * @property string|null $image_url
 * @property string $posted_at
 * @property string $checksum
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Feed $feed
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereChecksum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereFeedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem wherePostedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FeedItem whereUserId($value)
 * @mixin \Eloquent
 * @method static Builder|FeedItem unread()
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
    ];

    /**
     * Get the prunable model query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function prunable()
    {
        return static::where('posted_at', '<=', now()->subMonths(config('app.months_after_pruning_feed_items')));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread(Builder $query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * @return bool
     */
    public function hasImage()
    {
        return $this->image_url && isImageUrl($this->image_url);
    }

    public function getFormattedPostedAtAttribute()
    {
        return $this->posted_at->format(__('date.datetime'));
    }
}

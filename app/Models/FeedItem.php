<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
        return static::where('posted_at', '<=', now()->subMonths(config('app.months_after_pruning_feed_items')));
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

    public function scopeFilteredByFeed($query, $filteredFeedId, Collection $readFeedItemIds)
    {
        return $query
            ->unread()
            ->when($readFeedItemIds->isNotEmpty(), function ($query) use ($readFeedItemIds) {
                return $query->orWhereIn('feed_items.id', $readFeedItemIds);
            })
            ->when($filteredFeedId, function ($query) use ($filteredFeedId) {
                return $query->where('feed_id', $filteredFeedId);
            })
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

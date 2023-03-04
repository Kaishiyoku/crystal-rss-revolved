<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FeedItem extends Model
{
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
     * @var array
     */
    protected $casts = [
        'posted_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('read_at', '<=', now()->subMonths(config('app.months_after_pruning_feed_items')));
    }

    public function feed(): BelongsTo
    {
        return $this->belongsTo(Feed::class);
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

    public function hasImage(): bool
    {
        return $this->image_url && Str::startsWith($this->image_mimetype, 'image/');
    }
}

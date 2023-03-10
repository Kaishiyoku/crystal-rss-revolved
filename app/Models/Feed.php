<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Feed
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $feed_url
 * @property string $site_url
 * @property string|null $favicon_url
 * @property string $name
 * @property string $language
 * @property string|null $last_checked_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FeedItem> $feedItems
 * @property-read int|null $feed_items_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Feed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereFaviconUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereFeedUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereLastCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereSiteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereUserId($value)
 * @mixin \Eloquent
 */
class Feed extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'feed_url',
        'site_url',
        'favicon_url',
        'name',
        'language',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function feedItems(): HasMany
    {
        return $this->hasMany(FeedItem::class);
    }
}

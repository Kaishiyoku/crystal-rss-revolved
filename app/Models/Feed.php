<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\Feed
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $feed_url
 * @property string $site_url
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $last_checked_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Feed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feed query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereFeedUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereLastCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereSiteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FeedItem[] $feedItems
 * @property-read int|null $feed_items_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FeedItem[] $unreadFeedItems
 * @property-read int|null $unread_feed_items_count
 * @property string|null $favicon_url
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereFaviconUrl($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[] $tokens
 * @property-read int|null $tokens_count
 * @property string|null $language
 * @method static \Illuminate\Database\Eloquent\Builder|Feed whereLanguage($value)
 * @method static \Database\Factories\FeedFactory factory(...$parameters)
 */
class Feed extends Model
{
    use HasApiTokens;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'category_id',
        'feed_url',
        'site_url',
        'favicon_url',
        'name',
        'language',
        'last_checked_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'last_checked_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function feedItems()
    {
        return $this->hasMany(FeedItem::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function unreadFeedItems()
    {
        return $this->hasMany(FeedItem::class)->unread();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $feed_url
 * @property string $site_url
 * @property string|null $favicon_url
 * @property string $name
 * @property string $language
 * @property bool $is_purgeable
 * @property \Illuminate\Support\Carbon|null $last_checked_at
 * @property \Illuminate\Support\Carbon|null $last_failed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FeedItem> $feedItems
 * @property-read int|null $feed_items_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\FeedFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereFaviconUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereFeedUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereIsPurgeable($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereLanguage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereLastCheckedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereLastFailedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereSiteUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Feed whereUserId($value)
 * @mixin \Eloquent
 */
class Feed extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'feed_url',
        'site_url',
        'favicon_url',
        'name',
        'language',
        'is_purgeable',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_purgeable' => 'bool',
        'last_checked_at' => 'datetime',
        'last_failed_at' => 'datetime',
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
        return $this->hasMany(FeedItem::class)->orderByDesc('posted_at');
    }
}

<?php

use App\Models\Feed;
use App\Models\FeedItem;
use App\Services\TypeScriptModelGenerator\Nodes\Type;
use App\Services\TypeScriptModelGenerator\Nodes\TypeProperty;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

it('builds a type property', function () {
    $sampleModel = new class extends Model {
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
         * @var array<int, string>
         */
        protected $appends = [
            'has_image',
            'is_read',
        ];

        /**
         * Does the feed item has an image?
         */
        protected function hasImage(): Attribute
        {
            return Attribute::make(
                get: fn (): bool => $this->image_url && Str::startsWith($this->image_mimetype, 'image/'),
            );
        }

        /**
         * Is the feed item read?
         */
        protected function isRead(): Attribute
        {
            return Attribute::make(
                get: fn () => $this->read_at,
            );
        }

        public function feed(): BelongsTo
        {
            return $this->belongsTo(Feed::class);
        }
    };

    expect((new TypeProperty(new FeedItem(), 'checksum'))->toString())->toBe('checksum: string;')
        ->and((new TypeProperty(new FeedItem(), 'posted_at'))->toString())->toBe('posted_at: string /** cast attribute */;')
        ->and((new TypeProperty(new FeedItem(), 'has_image'))->toString())->toBe('has_image: boolean /** model attribute */;')
        ->and((new TypeProperty(new FeedItem(), 'non_existent_field'))->toString())->toBe('non_existent_field: unknown /** no return types found */;')
        ->and((new TypeProperty($sampleModel, 'is_read'))->toString())->toBe('is_read: unknown /** no return types found */;');
});

it('builds a type property from inherited type config', function () {
    expect(TypeProperty::fromInheritedTypeConfig([
        'model' => FeedItem::class,
        'name' => 'my_field',
        'types' => ['number', 'null'],
    ])->toString())->toBe('my_field: number | null;');
});

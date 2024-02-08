<?php

use App\Models\FeedItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Intervention\Image\Exceptions\DecoderException;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('feed_items', function (Blueprint $table) {
            $table->string('blur_hash')->nullable()->after('image_mimetype');
        });

        // generate blur hashes for existing feed items
        FeedItem::unread()->whereNotNull('image_url')->each(function (FeedItem $feedItem) {
            try {
                $feedItem->fill([
                    'blur_hash' => generateBlurHashByUrl($feedItem->image_url),
                ]);

                $feedItem->save();
            } catch (DecoderException) {
                // nothing to do here
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feed_items', function (Blueprint $table) {
            $table->dropColumn('blur_hash');
        });
    }
};

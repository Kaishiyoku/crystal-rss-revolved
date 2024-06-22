<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::table('feed_items')
            ->whereNull('read_at')
            ->whereNotNull('image_url')
            ->orderBy('id')
            ->each(function ($feedItem) {
                try {
                    DB::table('feed_items')
                        ->where('id', $feedItem->id)
                        ->update([
                            'blur_hash' => generateBlurHashByUrl($feedItem->image_url),
                        ]);
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

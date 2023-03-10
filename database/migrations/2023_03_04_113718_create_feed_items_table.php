<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feed_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id')->constrained();
            $table->string('checksum')->unique();
            $table->string('url', 512);
            $table->string('title');
            $table->string('image_url')->nullable();
            $table->string('image_mimetype')->nullable();
            $table->string('description', 2048)->nullable();
            $table->dateTime('posted_at');
            $table->dateTime('read_at')->index()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_items');
    }
};

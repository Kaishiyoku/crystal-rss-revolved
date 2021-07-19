<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_id');
            $table->string('url');
            $table->string('title');
            $table->string('image_url')->nullable();
            $table->dateTime('posted_at');
            $table->string('checksum');
            $table->dateTime('read_at')->nullable();
            $table->timestamps();

            $table->foreign('feed_id')->references('id')->on('feeds');

            $table->unique('checksum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_items');
    }
}

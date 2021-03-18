<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingArticlesImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_articles_images', function (Blueprint $table) {
            $table->unsignedInteger('image_id');
            $table->unsignedInteger('pending_articles_id');

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
            $table->foreign('pending_articles_id')->references('id')->on('pending_articles')->onDelete('cascade');

            //SETTING THE PRIMARY KEYS
            $table->primary(['image_id', 'pending_articles_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_articles_images');
    }
}

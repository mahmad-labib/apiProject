<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePendingArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('creator_id');
            $table->string('section_id');
            $table->string('title');
            $table->text('content');
            $table->text('comment')->default(null);
            $table->text('state')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_articles');
    }
}

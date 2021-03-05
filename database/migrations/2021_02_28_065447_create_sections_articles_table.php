<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionsArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections_articles', function (Blueprint $table) {
            $table->unsignedInteger('section_id');
            $table->unsignedInteger('article_id');

            //FOREIGN KEY CONSTRAINTS
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
 
            //SETTING THE PRIMARY KEYS
            $table->primary(['section_id','article_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections_articles');
    }
}

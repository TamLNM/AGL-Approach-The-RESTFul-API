<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ArticleCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_category', function (Blueprint $table) {
            $table->integer('category_id')->nullable(false);
            $table->integer('article_id')->nullable(false);

            /* Set primary key */
            $table->primary(['category_id', 'article_id']);
            
            /* Set foreign key */
            /*
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('article_id')->references('id')->on('articles');
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_category');
    }
}

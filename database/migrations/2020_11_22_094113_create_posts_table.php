<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string("header");
            $table->text("description");
            $table->string("content");
            $table->integer('views');
            $table->integer('uid');
            $table->integer('likes');
            $table->unsignedInteger('category_id');
            $table->integer('type_id');
            $table->integer('currentViews');
            $table->boolean("is_published");
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
        Schema::dropIfExists('posts');
    }
}

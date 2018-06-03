<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditorPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editor_pages', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('parent_page_id')->nullable();
          $table->integer('page_content_id');
          $table->string('title');
          $table->string('path');
          $table->json('metadata')->nullable();
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
        Schema::dropIfExists('editor_pages');
    }
}

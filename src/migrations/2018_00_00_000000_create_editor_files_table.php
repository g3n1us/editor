<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditorFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editor_files', function (Blueprint $table) {
          $table->increments('id');
          $table->string('filename');
          $table->string('path');
          $table->string('bucket')->nullable();
          $table->string('mime_type')->default('image/jpeg');
          $table->string('extension', 8)->default('jpg');
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
        Schema::dropIfExists('editor_files');
    }
} 

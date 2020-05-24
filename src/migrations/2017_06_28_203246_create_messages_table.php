<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
/*
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('ready')->default(false);
            $table->string('subject');
            $table->string('template')->nullable();
            $table->text('body');
            $table->string('reply_to')->nullable();
            $table->dateTime('send_date');
            $table->json('config')->nullable();
            $table->timestamps();
        });
*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('messages');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('bookings', function (Blueprint $table) {
        $table->bigIncrements('id');

        // http://stackoverflow.com/questions/22615926/migration-cannot-add-foreign-key-constraint-in-laravel#answer-22616100
        $table->bigInteger('room_id')->unsigned();
        $table->foreign('room_id')->references('id')->on('rooms');
        $table->string('reserved_by');
        $table->string('on_behalf_of')->nullable();
        $table->boolean('confirmed')->default(false);
        $table->dateTime('start');
        $table->dateTime('end');
        $table->enum('status', ['unconfirmed', 'confirmed', 'cancelled'])
              ->default('unconfirmed');
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
        Schema::drop('bookings');
    }
}

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
        $table->bigInteger('room_id');
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

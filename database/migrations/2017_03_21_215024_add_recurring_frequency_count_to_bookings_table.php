<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecurringFrequencyCountToBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
          $table->string('recursion_frequency')->after('participants');
          $table->integer('recursion_count')->after('recursion_frequency');
          $table->string('recursion_start_date')->after('recursion_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
          $table->dropColumn('recursion_frequency');
          $table->dropColumn('recursion_count');
          $table->dropColumn('recursion_start_date');
        });
    }
}

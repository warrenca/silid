<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $rooms = [
        'Conference Room' => 'A meeting room for a lot of people. Equipped with video conference devices.',
        'Meeting Room A' => 'A meeting room for four people',
        'Meeting Room B' => 'A meeting room for up to six people',
      ];

      foreach ($rooms as $name => $description) {
        DB::table('rooms')->insert([
            'name' => $name,
            'description' => $description,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
      }
    }
}

<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\Booking as Booking;

$app->get('/', function () use ($app) {
  return $app->make('view')->make('bookings/index', ['name'=>'warren']);
});

$app->post('/', function () use ($app) {
  $room_id = $app->request->room_id;
  $reserved_by = $app->request->reserved_by;
  $booking_date = $app->request->booking_date;
  $booking_time = $app->request->booking_time;
  $booking_duration = $app->request->booking_duration;

  $start_ts = strtotime("$booking_time $booking_date");
  $start = date('Y-m-d H:i:s', $start_ts);

  $end_ts = $start_ts + $booking_duration;
  $end = date('Y-m-d H:i:s', $end_ts);

  // http://laraveldaily.com/eloquent-date-filtering-wheredate-and-other-methods/
  $currentBookings = Booking::where('room_id', $room_id)
                ->where('confirmed', 1)
                ->whereDay('start', date('d', $start_ts))
                ->whereMonth('start', date('m', $start_ts))
                ->whereYear('start', date('Y', $start_ts))
                ->get();

  foreach ($currentBookings as $currentBooking) {
    $booking_start_ts = strtotime($currentBooking->start);
    $booking_end_ts = strtotime($currentBooking->end);

    // http://stackoverflow.com/questions/13387490/determining-if-two-time-ranges-overlap-at-any-point
    if ($booking_start_ts < $end_ts && $booking_end_ts > $start_ts) {
      echo "confict";
      echo "redirect...";
      die();
    }
  }

  $booking = new Booking;
  $booking->room_id = $room_id;
  $booking->reserved_by = $reserved_by;
  $booking->start = $start;
  $booking->end = $end;
  $booking->save();
  echo "Your booking has been saved but you must confirm it on your email to lock the room and timing. To cancel, click this link.";
  die();
});

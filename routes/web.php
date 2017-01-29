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

$app->get('/', function() use ($app) {
  try {
    \Socialite::driver('google')->userFromToken($_SESSION['token']);
    return redirect('booking');
  } catch (\Exception $e) {
    return redirect('login');
  }
});

$app->get('/login', function() use ($app) {
  return $app->make('view')->make('login', ['allowed_domains'=>env('SILID_ALLOWED_DOMAINS')]);
});

$app->get('/booking', function () use ($app) {
  try {
    \Socialite::driver('google')->userFromToken($_SESSION['token']);
  } catch (\Exception $e) {
    return redirect('login');
  }

  $booking_errors = [];
  $email = "";

  if (isset($_SESSION['booking_errors'])) {
      $booking_errors = $_SESSION['booking_errors'];
      unset($_SESSION['booking_errors']);
  }

  try {
    $user = \Socialite::driver('google')->userFromToken($_SESSION['token']);
    $email = $user->email;
  } catch (\Exception $e) {

  }
  return $app->make('view')->make('booking/index', ['email' => $email, 'booking_errors' => $booking_errors]);
});

$app->post('/booking', function () use ($app) {
  $validator = ValidatorX::make($app->request->all(), [
    'room_id' => 'required|numeric',
    'reserved_by' => 'required|email',
    'booking_date' => 'required',
    'booking_time' => 'required',
    'booking_duration' => 'required|numeric',
  ],
  [
    'room_id.required' => 'The room is required'
  ]);

  if ($validator->fails()) {
    try {
      $_SESSION['booking_errors'] = $validator->errors()->all();
    } catch (\Exception $e) {
      dd($e->getMessage());
    }

    return redirect('booking');
  }


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

/*
 * https://github.com/laravel/socialite
 * http://socialiteproviders.github.io/providers/google+/
 * https://laracasts.com/discuss/channels/lumen/cant-get-config-data-in-lumen
 * https://lumen.laravel.com/docs/5.4/configuration#configuration-files)
 * http://itsolutionstuff.com/post/solved-access-not-configured-google-api-truncated-on-google-console-developerexample.html
 * http://stackoverflow.com/questions/35536548/unable-to-use-laravel-socialite-with-lumen
 */
$app->get('/socialite/google/login', function () use ($app) {
  return \Socialite::driver('google')->stateless(false)->redirect();
});

$app->get('/socialite/google/callback', function () use ($app) {
  $user = \Socialite::driver('google')->stateless(false)->user();
  // OAuth Two Providers
  $token = $user->token;
  $expiresIn = $user->expiresIn;

  $_SESSION['token'] = $token;
  $_SESSION['expiresIn'] = $expiresIn;
  return redirect('/booking');
});

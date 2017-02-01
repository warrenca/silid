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

use Illuminate\Support\Facades\Mail;
use Hashids\Hashids;

use App\Mail\Confirmation as Confirmation;
use App\Mail\Locked as Locked;
use App\Booking as Booking;
use App\Room as Room;

/* Root URL */
$app->get('/', function() use ($app) {
  try {
    \Socialite::driver('google')->userFromToken($_SESSION['token']);
    return redirect('booking');
  } catch (\Exception $e) {
    return redirect('login');
  }
});

/* Login page */
$app->get('/login', function() use ($app) {
  $errors = [];
  if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
  }

  return $app->make('view')->make('login', ['allowed_domains'=>env('SILID_ALLOWED_DOMAINS'), 'errors' => $errors]);
});

/* Booking form */
$app->get('/booking', function () use ($app) {
  try {
    \Socialite::driver('google')->userFromToken($_SESSION['token']);
  } catch (\Exception $e) {
    return redirect('login');
  }

  $booking_errors = [];
  $booking_parameters = [];
  $success_message = "";
  $email = "";

  if (isset($_SESSION['booking_errors'])) {
      $booking_errors = $_SESSION['booking_errors'];
      unset($_SESSION['booking_errors']);
  }

  if (isset($_SESSION['booking_parameters'])) {
    $booking_parameters = $_SESSION['booking_parameters'];
  }

  if (isset($_SESSION['success'])) {
      $success_message = $_SESSION['success'];
      unset($_SESSION['success']);
  }

  try {
    $user = \Socialite::driver('google')->userFromToken($_SESSION['token']);
    $email = $user->email;
  } catch (\Exception $e) {

  }

  return $app->make('view')->make('booking/index',
                                  [
                                    'email' => $email,
                                    'booking_errors' => $booking_errors,
                                    'rooms' => Room::all(),
                                    'booking_durations' => $app['config']['booking.duration'],
                                    'booking_parameters' => $booking_parameters,
                                    'success_message' => $success_message
                                  ]
                                );
});

/* Booking reset form */
$app->get('/booking/reset', function () use ($app) {
  unset($_SESSION['booking_parameters']);
  return redirect('booking');
});

/* Booking saving */
$app->post('/booking', function () use ($app) {
  try {
    \Socialite::driver('google')->userFromToken($_SESSION['token']);
  } catch (\Exception $e) {
    return redirect('login');
  }

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

  $_SESSION['booking_parameters'] = $app->request->all();
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
      $booking_link = generateBookingLink($currentBooking->id);
      $_SESSION['booking_errors'] = ["An active room booking is already reserved on the timing you selected. View it $booking_link"];
      return redirect('booking');
    }
  }

  $booking = new Booking;
  $booking->room_id = $room_id;
  $booking->reserved_by = $reserved_by;
  $booking->start = $start;
  $booking->end = $end;
  $booking->save();

  $_SESSION['success'] = "An email has been sent to you for instruction to confirm and lock-in your booking. Please check it out right away.";
  Mail::to($reserved_by)
        ->send(new Confirmation($booking));
  unset($_SESSION['booking_parameters']);
  return redirect('booking');
});

/* Booking Confirmation */
$app->get('/booking/confirmation/{confirmation_id}', function ($confirmation_id) use ($app) {
  $hashids = new Hashids(env('APP_KEY'), config('booking.hashes.CONFIRMATION_HASH_LENGTH'));
  $booking_id = $hashids->decode($confirmation_id);

  try {
    $booking = Booking::find($booking_id)->first();
    $booking->confirmed = 1;
    $booking->status = 'confirmed';
    $booking->save();

    if ($booking->count() > 0) {
      unset($_SESSION['booking_errors']);
      $_SESSION['success'] = "Your booking is confirmed!";
      $hashids = new Hashids(env('APP_KEY'), config('booking.hashes.VIEW_HASH_LENGTH'));
      //
      // Mail::to($booking->reserved_by)
      //       ->send(new Locked($booking));

      return redirect('booking/view/' . $hashids->encode($booking->id));
    }
  } catch(\Exception $e) {
    unset($_SESSION['success']);
    $_SESSION['booking_errors'] = ['That room booking do not exist.'];
    return redirect('booking');
  }
});

/* Booking Confirmation */
$app->get('/booking/view/{booking_id_param}', function ($booking_id_param) use ($app) {
  try {
    $hashids = new Hashids(env('APP_KEY'), config('booking.hashes.VIEW_HASH_LENGTH'));
    $booking = Booking::find($hashids->decode($booking_id_param))->first();

    if ($booking->count() > 0)
    {
      $success_message = '';
      if (isset($_SESSION['success'])) {
          $success_message = $_SESSION['success'];
          unset($_SESSION['success']);
      }

      $hashids = new Hashids(env('APP_KEY'), config('booking.hashes.CONFIRMATION_HASH_LENGTH'));
      $confirmation_id = $hashids->encode($booking->id);

      return $app->make('view')->make('booking/view',
                                      [
                                        'booking' => $booking,
                                        'confirmation_id' => $confirmation_id,
                                        'success_message' => $success_message
                                      ]
                                    );
    }

    return redirect('booking?try-booking-view');
  } catch(\Exception $e) {
    return redirect('booking?catch-booking-view');
    dd($e->getMessage());
  }

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

/* Socialite Google callback - after google login */
$app->get('/socialite/google/callback', function () use ($app) {
  try {
    $user = \Socialite::driver('google')->stateless(false)->user();

    $regex = '/@((([^.]+)\.)+)([a-zA-Z]{3,}|[a-zA-Z.]{5,})/';
    preg_match($regex, $user->email, $matches);
    $hostname = substr($matches[0], 1);

    if (! in_array($hostname, explode(",",env('SILID_ALLOWED_DOMAINS')))) {
      $_SESSION['errors'] = ['Your email is not part of the allowed domains. Please sign-in with an email from the allowed domains.'];
      return redirect('login');
    }

    // OAuth Two Providers
    $token = $user->token;
    $expiresIn = $user->expiresIn;

    $_SESSION['token'] = $token;
    $_SESSION['expiresIn'] = $expiresIn;
    return redirect('booking');
  } catch (\Exception $e) {
    return redirect('login');
  }
});

/* Logout */
$app->get('/logout', function () use ($app) {
  unset($_SESSION['token']);
  unset($_SESSION['expiresIn']);
  return redirect('login');
});

/* generateBookingLink */
function generateBookingLink($booking_id) {
  $hostname = env('SILID_HOSTNAME');
  $hashids = new Hashids(env('APP_KEY'), config('booking.hashes.VIEW_HASH_LENGTH'));
  $booking_id_hashed = $hashids->encode($booking_id);

  return "<a href=\"$hostname/booking/view/$booking_id_hashed\">here</a>";
}

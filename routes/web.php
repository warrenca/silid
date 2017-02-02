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

/* Booking form */
$app->get('/booking', 'BookingController@index');
/* Booking saving */
$app->post('/booking', 'BookingController@store');
/* Booking reset form */
$app->get('/booking/reset', 'BookingController@reset');


/* Booking Confirmation */
$app->get('/booking/confirmation/{confirmation_id}', 'BookingController@confirmation');

/* View Booking */
$app->get('/booking/view/{booking_id_param}', 'BookingController@view');

/*
 * https://github.com/laravel/socialite
 * http://socialiteproviders.github.io/providers/google+/
 * https://laracasts.com/discuss/channels/lumen/cant-get-config-data-in-lumen
 * https://lumen.laravel.com/docs/5.4/configuration#configuration-files)
 * http://itsolutionstuff.com/post/solved-access-not-configured-google-api-truncated-on-google-console-developerexample.html
 * http://stackoverflow.com/questions/35536548/unable-to-use-laravel-socialite-with-lumen
 */
$app->get('/socialite/google/login', 'AuthController@googleLogin');

/* Socialite Google callback - after google login */
$app->get('/socialite/google/callback', 'AuthController@googleCallback');

/* Login page */
$app->get('/login', 'AuthController@login');

/* Logout */
$app->get('/logout', 'AuthController@logout');

/* generateBookingLink */
function generateBookingLink($booking_id) {
  $hostname = env('SILID_HOSTNAME');
  $hashids = new Hashids(env('APP_KEY'), config('booking.hashes.VIEW_HASH_LENGTH'));
  $booking_id_hashed = $hashids->encode($booking_id);

  return "<a href=\"$hostname/booking/view/$booking_id_hashed\">here</a>";
}

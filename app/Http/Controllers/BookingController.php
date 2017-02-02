<?php

namespace App\Http\Controllers;

use App\Mail\Confirmation as Confirmation;
use Illuminate\Support\Facades\Mail;
use App\Booking as Booking;
use App\Room as Room;
use Hashids\Hashids;
use ValidatorX;

class BookingController extends Controller
{
  public function index()
  {
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

    return app()->make('view')->make('booking/index',
                                    [
                                      'email' => $email,
                                      'booking_errors' => $booking_errors,
                                      'rooms' => Room::all(),
                                      'booking_durations' => config('booking.duration'),
                                      'booking_parameters' => $booking_parameters,
                                      'success_message' => $success_message
                                    ]
                                  );
  }

  public function store()
  {
    try {
      \Socialite::driver('google')->userFromToken($_SESSION['token']);
    } catch (\Exception $e) {
      return redirect('login');
    }

    $validator = ValidatorX::make(app()->request->all(), [
      'room_id' => 'required|numeric',
      'reserved_by' => 'required|email',
      'booking_date' => 'required',
      'booking_time' => 'required',
      'booking_duration' => 'required|numeric',
    ],
    [
      'room_id.required' => 'The room is required'
    ]);

    $_SESSION['booking_parameters'] = app()->request->all();
    if ($validator->fails()) {
      try {
        $_SESSION['booking_errors'] = $validator->errors()->all();
      } catch (\Exception $e) {
        dd($e->getMessage());
      }

      return redirect('booking');
    }

    $room_id = app()->request->room_id;
    $reserved_by = app()->request->reserved_by;
    $booking_date = app()->request->booking_date;
    $booking_time = app()->request->booking_time;
    $booking_duration = app()->request->booking_duration;

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
  }

  public function reset()
  {
    unset($_SESSION['booking_parameters']);
    return redirect('booking');
  }

  public function ($confirmation_id) {
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
  }

  public function view($booking_id_param)
  {
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
  }
}

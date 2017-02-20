<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

use App\Booking;
use Hashids\Hashids;

class Confirmation extends Mailable
{
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // https://github.com/laravel/lumen/blob/60776a0d763ac8a255ac14008e4edda25d2224b1/.env.example
        // https://laracasts.com/discuss/channels/lumen/lumen-52-mail-not-working
        $hostname = env('SILID_HOSTNAME');
        $hashids = new Hashids(env('APP_KEY'), config('booking.hashes.VIEW_HASH_LENGTH'));

        $booking_view_link = "$hostname/booking/view/" . $hashids->encode($this->booking->id);
        return $this->view('emails.cerberus-responsive')
                    ->subject( env('COMPANY_NAME') . ' Room Booking Reference Code: ' . str_pad($this->booking->id, 10, "0", STR_PAD_LEFT))
                    ->with([
                        'booking_view_link' => $booking_view_link,
                        'purpose' => $this->booking->purpose,
                        'booking_reserved_by' => $this->booking->reserved_by,
                        'booking_room_id' => $this->booking->room->id,
                        'booking_room_name' => $this->booking->room->name,
                        'booking_room_description' => $this->booking->room->description,
                        'booking_start' => date('F d, Y @H:i A', strtotime($this->booking->start)),
                        'booking_end' => date('F d, Y @H:i A', strtotime($this->booking->end)),
                    ]);
    }
}

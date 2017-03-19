<?php

namespace App\Jobs;
use App\Booking;
use App\Mail\Confirmation as MailConfirmation;
use Illuminate\Support\Facades\Mail;

class SendConfirmationEmailQ extends Job
{
    public $email_to;
    public $booking;
    public $eventCreator;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email_to, Booking $booking, $eventCreator)
    {
        $this->email_to = $email_to;
        $this->booking = $booking;
        $this->eventCreator = $eventCreator;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Mail::to($this->email_to)
            ->send(new MailConfirmation($this->booking, $this->eventCreator));
    }
}

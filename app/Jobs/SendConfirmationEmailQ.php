<?php

namespace App\Jobs;
use App\Mail\Confirmation;
use Illuminate\Support\Facades\Mail;

class SendConfirmationEmailQ extends Job
{
    protected $mailConfirmation;
    protected $email_to;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email_to, Confirmation $mailConfirmation)
    {
        $this->email_to = $email_to;
        $this->mailConfirmation = $mailConfirmation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      Mail::to($this->email_to)
            ->send($this->mailConfirmation);
    }
}

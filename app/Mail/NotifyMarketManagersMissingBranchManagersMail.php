<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Oracle;

class NotifyMarketManagersMissingBranchManagersMail extends Mailable
{
    use Queueable, SerializesModels;
    public Oracle $manager;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Oracle $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.notify-market-managers-missing-branch-managers-mail');
    }
}

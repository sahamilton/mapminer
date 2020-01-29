<?php

namespace App\Mail;

use App\Feedback;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedBackOpened extends Mailable
{
    use Queueable, SerializesModels;
    public $feedback;
    public $user;

    /**
     * [__construct description].
     *
     * @param Feedback $feedback [description]
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
        $this->user = User::with('person')->findOrFail(auth()->user()->id);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.feedbackopened')
            ->to($this->feedback->providedBy->email)
            ->cc(config('mapminer.system_contact'))
            ->bcc(config('mapminer.developer_email'))
            ->subject('Feedback Reopened');
    }
}

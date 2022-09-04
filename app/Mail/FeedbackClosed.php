<?php

namespace App\Mail;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackClosed extends Mailable
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
        if (! $this->feedback->providedBy) {
            return $this->markdown('emails.feedbackclosed')
                ->to(config('mapminer.system_contact'))
                ->cc(config('mapminer.developer_email'))
                ->subject('Feedback Closed');
        } else {
            return $this->markdown('emails.feedbackclosed')
                ->to($this->feedback->providedBy->email)
                ->cc(config('mapminer.system_contact'))
                ->bcc(config('mapminer.developer_email'))
                ->subject('Feedback Closed');
        }
        
    }
}

<?php

namespace App\Mail;

use App\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedBackResponseEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $feedback;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       
        return $this->markdown('emails.feedbackresponse')
        ->to($this->feedback->providedBy->email)
        ->cc(config('mapminer.system_contact'))
        ->bcc(config('mapminer.developer_email'))
        ->subject('New Feedback');
    }
}

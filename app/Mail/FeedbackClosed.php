<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Feedback;
use App\User;

class FeedbackClosed extends Mailable
{
    use Queueable, SerializesModels;
    public $feedback;
    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
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
         return $this->markdown('emails.feedbackclosed')
            ->to($this->feedback->providedBy->email)
            ->cc(config('mapminer.system_contact'))
            ->bcc(config('mapminer.developer_email'))
            ->subject('Feedback Closed');

        
    }
}

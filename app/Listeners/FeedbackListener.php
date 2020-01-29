<?php

namespace App\Listeners;

use App\Events\FeedbackEvent;
use App\Feedback;
use App\Mail\FeedbackClosed;
use App\Mail\FeedbackComment;
use App\Mail\FeedbackOpened;
use App\Mail\FeedbackResponse;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class FeedbackListener
{
    /**
     * Handle the event.
     *
     * @param  FeedbackEvent  $event
     * @return void
     */
    public function handle(FeedbackEvent $event)
    {
        if ($event->feedback->status == 'closed') {
            Mail::to($event->feedback->providedBy->email)
                ->cc(config('mapminer.system_contact'), config('mapminer.developer_email'))
                ->queue(new FeedbackClosed($event->feedback));
        } else {
            Mail::to($event->feedback->providedBy->email)
                ->cc(config('mapminer.system_contact'), config('mapminer.developer_email'))
                ->queue(new FeedbackComment($event->feedback));
        }
    }
}

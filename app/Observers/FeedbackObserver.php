<?php

namespace App\Observers;

use App\Models\Feedback;
use App\Mail\FeedbackToBesnappy;

class FeedbackObserver
{
    //

    public function created(Feedback $feedback)
    {

        \Mail::to(config('besnappy.email_address'))
            ->send(new FeedbackToBesnappy($feedback));
    }
}

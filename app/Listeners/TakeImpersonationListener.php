<?php

namespace App\Listeners;

use \Lab404\Impersonate\Events\TakeImpersonation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
class TakeImpersonationListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TakeImpersonate  $event
     * @return void
     */
    public function handle(LeaveImpersonation $event)
    {
        Log::($event->impersonator->email ." is impersonating " . $event->impersonated->email . " @ " . now());
    }
}

<?php

namespace App\Listeners;

use \Lab404\Impersonate\Events\TakeImpersonation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
class LeaveImpersonationListener
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
     * @param  LeaveImpersonate  $event
     * @return void
     */
    public function handle(TakeImpersonation  $event)
    {
        Log::($event->impersonator->email ." is no longer impersonating " . $event->impersonated->email . " @ " . now());
    }
}

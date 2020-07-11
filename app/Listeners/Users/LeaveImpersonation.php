<?php

namespace App\Listeners\Users;

use App\Events\LeaveImpersonation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class LeaveImpersonation
{
    
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  LeaveImpersonation  $event
     * @return void
     */
    public function handle(LeaveImpersonation $event)
    {
        
        session()->forget(['manager','branch']);
    }
}

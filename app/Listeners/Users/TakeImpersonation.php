<?php

namespace App\Listeners\Users;

use App\Events\TakeImpersonation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;


class TakeImpersonation
{
 
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  TakeImpersonation  $event
     * @return void
     */
    public function handle(TakeImpersonation $event)
    {
        
        session()->forget(['manager','branch']);
    }
}

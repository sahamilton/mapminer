<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\Users\UpdateLastLoggedInAt',
        ],
        
        'App\Events\FeedbackEvent' => [
            'App\Listeners\FeedbackListener',
        ],
        '\Lab404\Impersonate\Events\TakeImpersonation'=>[
            'App\Listeners\TakeImpersonationListener',
        ],
        '\Lab404\Impersonate\Events\LeaveImpersonation'=>[
            'App\Listeners\LeaveImpersonationListener',
        ],
        
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

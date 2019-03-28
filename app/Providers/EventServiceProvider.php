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
        'App\Listeners\Users\UpdateLastLoggedInAt'],
        
        'App\Events\FeedbackEvent' => [
        'App\Listeners\FeedbackListener',
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
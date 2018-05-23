<?php

namespace App\Providers;
use App\Observers\PersonObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Person;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {


        Schema::defaultStringLength(191);
        Person::observe(PersonObserver::class);


    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      

        /*if ($this->app->environment() == 'local') {
            $this->app->register('Laracasts\Generators\GeneratorsServiceProvider');
        }*/
        if (config('app.debug') ) {
            $this->app->register('VIACreative\SudoSu\ServiceProvider');
        }
        
       
    }
}
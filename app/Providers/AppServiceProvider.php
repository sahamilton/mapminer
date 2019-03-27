<?php

namespace App\Providers;
use App\Observers\PersonObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Person;
use App\Branch;
use App\Location;
use App\Project;
use App\Lead;



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
        Relation::morphMap([
            'branch'  => Branch::class,
            'location'  => Location::class,
            'project' =>Project::class,
            'lead' => Lead::class,
            'person'=>Person::class,
        ]);


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
       
        
       
    }
}
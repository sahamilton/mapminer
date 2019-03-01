<?php

namespace App;

use Carbon\Carbon;
use App\Presenters\LocationPresenter;
use McCool\LaravelAutoPresenter\HasPresenter;
use Geocoder\Laravel\Facades\Geocoder;
use Illuminate\Database\Eloquent\SoftDeletes;

class Weblead extends Model implements HasPresenter
{
    use  Geocode;

    public $table= 'webleads';
              
    public $fillable = [
            'rating',
            'jobs',
            'time_frame',
            'multiple'];
   

    public function getPresenterClass()
    {
        return LocationPresenter::class;
    }
}

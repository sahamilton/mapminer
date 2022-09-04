<?php

namespace App\Models;

use App\Presenters\LocationPresenter;
use Carbon\Carbon;
use Geocoder\Laravel\Facades\Geocoder;
use Illuminate\Database\Eloquent\SoftDeletes;
use McCool\LaravelAutoPresenter\HasPresenter;

class WebLead extends Model implements HasPresenter
{
    use  Geocode;

    public $table = 'webleads';

    public $fillable = [
            'rating',
            'jobs',
            'time_frame',
            'multiple', ];

    public function getPresenterClass()
    {
        return LocationPresenter::class;
    }
}

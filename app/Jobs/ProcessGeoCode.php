<?php

namespace App\Jobs;

use App\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessGeoCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $people;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($people)
    {
        $this->people = $people;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->people as $person) {
            $geoCode = $this->_getLatLng($person);
            if ($geoCode) {
                $data['geostatus'] = true;
                $data['lat'] = $geoCode['lat'];
                $data['lng'] = $geoCode['lng'];
            } else {
                $dat['geostatus'] = false;
            }
            $person->update($data);
        }
    }
    /**
     * Geocode Persons address
     * 
     * @param Object Person $person [description]
     * 
     * @return [type]         [description]
     */
    private function _getLatLng($person)
    {
      
        $geoCode = app('geocoder')->geocode($person->fullAddress())->get();
       
        return $person->getGeoCode($geoCode);
    }
}

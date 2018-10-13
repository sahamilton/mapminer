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

    protected $person;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $address = $this->getAddress();
        $geoCode = $this->getLatLng($address);
        if($geoCode){

            $data['geostatus'] = true;
            $data['lat'] = $geoCode['lat'];
            $data['lng'] = $geoCode['lng'];
            }else{
                $dat['geostatus'] = false;
            }
        $this->person->update($data);
    }

    private function getLatLng($address)
    {
        $geoCode = app('geocoder')->geocode($address)->get();
       
        return $this->person->getGeoCode($geoCode);
    }

    private function getAddress(){

        return trim(str_replace('  ',' ',$this->person->address . " " . $this->person->city . " ". $this->person->state ." " . $this->person->zip));
    }
}

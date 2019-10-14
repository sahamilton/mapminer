<?php

namespace App\Jobs;

use App\AddressPerson;
use app\Person;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Database\Eloquent\Collection;

class AssignLeadsToPeople implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $addresses;
    public $roles;
    public $distance;
    public $limit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Collection $addresses,
        Array $roles,
        $limit,
        $distance
    ) {
        $this->addresses = $addresses;
        $this->roles = $roles;
        $this->limit = $limit;
        $this->distance = $distance;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $count = [];
        foreach ($this->addresses as $address) {
            
            $people = Person::withRoles($this->roles)->nearby($address, $this->distance, $this->limit)->get();
            
            foreach ($people as $person) {

                if (isset($count[$person->id])) {
                    $count[$person->id]++;
                } else {
                    $count[$person->id] = 1;
                }
               
                AddressPerson::insert(['address_id'=>$address->id, 'person_id'=>$person->id, 'status_id'=>2, 'created_at' => Carbon::now()]);
            }
            
            
        }
    }
}

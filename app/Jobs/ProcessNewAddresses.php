<?php

namespace App\Jobs;

use App\Address;
use App\OrderImport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessNewAddresses implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $import;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(OrderImport $import)
    {
        $this->import = $import;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
            $data = $this->import->toArray();
            
            $data['addressable_type'] = 'customer';
            
            $address = Address::create($data);
            
            $this->import->update(['address_id'=>$address->id]);
            
    }
}

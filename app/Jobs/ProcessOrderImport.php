<?php

namespace App\Jobs;

use App\OrderImport;
use App\Address;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessOrderImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $import;
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
    public function handle(Address $address)
    {
           
            
            $address = $address->findOrFail($this->import->address_id);
           
            $data = ['period'=>'201811','orders'=>$this->import->orders];
         
            $address->orders()->attach($this->import->branch_id, $data);
    }
}

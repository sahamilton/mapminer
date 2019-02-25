<?php

namespace App\Jobs;

use App\OrderImport;
use App\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessNewCompanies implements ShouldQueue
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
      
        if (! $company = Company::where('customer_id', '=', $this->import->customer_id)->first()) {
            $data = [];
            $data['customer_id'] = $this->import->customer_id;
            $data['accounttypes_id'] = $this->import->accounttypes_id;
            $data['companyname'] = $this->import->businessname;
            $company = Company::firstOrCreate($data);
        }
        $this->import->update(['company_id'=>$company->id]);
    }
}

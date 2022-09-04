<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\OrderImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNewCompanies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $import;

    /**
     * [__construct description].
     *
     * @param OrderImport $import [description]
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
        if (! $company = Company::where(
            'customer_id', '=', $this->import->customer_id
        )->first()
        ) {
            $data = [];
            $data['customer_id'] = $this->import->customer_id;
            $data['accounttypes_id'] = $this->import->accounttypes_id;
            $data['companyname'] = $this->import->businessname;
            $company = Company::firstOrCreate($data);
        }
        $this->import->update(['company_id'=>$company->id]);
    }
}

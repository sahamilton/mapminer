<?php

namespace App\Jobs;

use \Carbon\Carbon;
use App\Person;
use App\Branch;
use App\Report;
use Mail;
use Excel;
use App\Mail\DailyBranchReport;
use App\Exports\DailyBranchExport;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class DailyBranch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $period;
    public $user;
    

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
       
        
        $this->period['from'] = Carbon::yesterday()->startOfDay();
        $this->period['to'] = Carbon::yesterday()->endOfDay();
        

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $class= str_replace("App\Jobs\\", "", get_class($this));
        $job = Report::where('job', $class)->with('distribution')->firstOrFail();
     
        foreach ($job->distribution as $recipient) {

            $this->person = Person::where('user_id', $recipient->id)->firstOrFail();
       
            $file = "/public/reports/".$this->person->firstname."_".$this->person->lastname."_dailyreport_". $this->period['from']->format('Y-m-d'). ".xlsx";
            
            Excel::store(
                new DailyBranchExport($this->period, [$this->person->id]), $file
            );
            $distribution = [$this->person->distribution()];
            Mail::to($distribution)->send(new DailyBranchReport($file, $this->period, $this->person));
        
        }
    }
}

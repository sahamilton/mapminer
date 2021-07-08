<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ConfirmFileTransfer;


class TransferFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $file; 
   
    public $path; 
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file =  storage_path('backups/'.$file);
        $this->path =  'dev.tbmapminer.com/storage/backups/'. $file;
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        \Storage::disk('sftp')->put($this->path, fopen($this->file, 'r+'));
        \Mail::queue(new ConfirmFileTransfer($this->file, $this->path));
    }
}

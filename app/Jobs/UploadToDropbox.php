<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;



class UploadToDropbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filesystem;
    public $file;
    public $path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        
        $this->file = $file;
        $this->path =  storage_path('backups/');
     
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
      \Storage::disk('dropbox')->put($this->file.".zip", fopen($this->path.$this->file.'.zip', 'r+'));
       
    }
}

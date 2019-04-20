<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ZipBackUp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $file;
    public $db;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        dd($file);
        $this->file = $file;
       
        $this->db = env('DB_DATABASE');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $path = storage_path('app/public/backups/');
        
        $zip = new \ZipArchive();
       
        $zip->open($path. $this->file . '.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFile($path.$this->file.".sql",$this->file.".sql");
        $zip->close();
    }
}

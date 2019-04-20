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
    public $path;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        
        $this->file = $file;
        $this->path =  public_path('storage/backups/');
        $this->db = env('DB_DATABASE');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
        $zip = new \ZipArchive();
        $zip->open($this->path. $this->file . '.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFile($this->path.$this->file.".sql",$this->file);
        $zip->close();
    }
}

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ZipBackUp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $file;
    public $db;
    public $path;

    /**
     * [__construct description].
     *
     * @param [type] $file [description]
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->path = storage_path('backups/');
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
        $zip->open($this->path.$this->file.'.zip', \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFile($this->path.$this->file.'.sql', $this->file.'.sql');
        $zip->close();
    }
}

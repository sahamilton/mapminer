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
        $zip_file = $this->db."-".now()->format('Y-m-d_h:i') .'zip'; 
        $zip = new \ZipArchive();
        $zip->open($zip_file, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFile(storage_path('backups/backup'), $this->file);
        $zip->close();
    }
}

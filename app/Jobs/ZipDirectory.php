<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ZipDirectory implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $directory;
    public $destination;

    /**
     * [__construct description].
     *
     * @param [type] $file [description]
     */
    public function __construct($destination, $directory)
    {
        $this->directory = $directory;
        $this->destination = storage_path('filebackups/').$destination.".zip";

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        @ray($this->directory, $this->destination);
        $zip = new \ZipArchive();
        $zip->open($this->destination, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->directory),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );
     
        foreach ($files as $name => $file) {
            @ray($name, $file->isDir());
            // Skip directories (they would be added automatically)
            if (! $file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($this->directory) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
    }
}

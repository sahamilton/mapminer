<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UploadToDropbox implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $filesystem;
    public $file;
    public $path;

    /**
     * [__construct description].
     *
     * @param string $file [description]
     */
    public function __construct($file, $directory = 'backups')
    {
        $this->file = $file;
        $this->path = storage_path($directory.'/');
    
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Storage::disk('dropbox')
            ->put('mapminer/'.$this->file, fopen($this->path.$this->file, 'r+'));
    }
}

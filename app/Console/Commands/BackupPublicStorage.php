<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Mail\ConfirmBackup;
use App\Mail\FailedBackup;
use App\Jobs\ZipBackUp;
use App\Jobs\TransferFileJob;
use App\Jobs\UploadToDropbox;
use Mail;
class BackupPublicStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:storage';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the Public Storage folders';

    protected $process;
    public $file;
    public $filename;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->filename = env('DB_DATABASE')."-storage-".now()->format('Y-m-d-H-i-s');
        $this->path = storage_path('filebackups/');
        $this->file = $this->path . $this->filename.'.zip';
        $this->backuptarget = storage_path('app/public');
        $this->command = 'zip -r '. $this->file . ' ' . $this->backuptarget;
        $this->process = Process::fromShellCommandline(
            sprintf($this->command)
        );
    }
    /**
     * [handle description]
     * 
     * @return [type] [description]
     */
    public function handle()
    {
        try { 
            $this->process->mustRun();
            $this->info('The backup has been processed successfully.');
            new UploadToDropbox($this->filename);
            Mail::queue(new ConfirmBackup($this->filename, $type='storage'));           
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process has failed.'. $exception);
            Mail::queue(new FailedBackup($this->filename));
        }
    }
}

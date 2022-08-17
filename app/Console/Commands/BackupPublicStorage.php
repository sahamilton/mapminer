<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Mail\ConfirmBackup;
use App\Mail\FailedBackup;
use App\Jobs\ZipDirectory;
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
    public $directory;
    public $filename;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->filename = env('DB_DATABASE')."-storage-".now()->format('Y-m-d-H-i-s');
        $this->directory = public_path('storage/avatars');
        
        
    }
    /**
     * [handle description]
     * 
     * @return [type] [description]
     */
    public function handle()
    {
        try {
             
            ZipDirectory::withChain(
                [
                    new UploadToDropbox($this->filename.'.zip', 'filebackups'),
                    //new TransferFileJob($this->filename.'.zip', 'filebackups'),
                    
                ]
            )->dispatch($this->filename, $this->directory);
                     
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process has failed.'. $exception);
            Mail::queue(new FailedBackup($this->filename));
        }
    }
}

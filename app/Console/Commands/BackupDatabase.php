<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Mail\ConfirmBackup;
use App\Mail\FailedBackup;
use App\Jobs\ZipBackUp;
use Mail;
class BackupDatabase extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Backup the database';

    protected $process;
    public $file;
    public $filename;

    public function __construct()
    {
        parent::__construct();
        
        $this->filename = env('DB_DATABASE')."-".now()->format('Y-m-d_h:i');
        
        $this->process = new Process(sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('app/public/backups/').$this->filename.'.sql'
        ));

        
    }

    public function handle()
    {
        try { 
            $this->process->mustRun();
            $this->info('The backup has been processed successfully.');
            ZipBackUp::dispatch($this->file)->onQueue('mapminer');
            Mail::queue(new ConfirmBackup($this->filename));
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process has failed.'. $exception);
            Mail::queue(new FailedBackup($this->file));
        }
    }
}
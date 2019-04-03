<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';

    protected $description = 'Backup the database';

    protected $process;

    public function __construct()
    {
        parent::__construct();
        $date = now()->format('Y-m-d hh:mm');
        dd(config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('backups/backup'.$date.'.sql'));
        $this->process = new Process(sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            storage_path('backups/backup'.$date.'.sql')
        ));
    }

    public function handle()
    {
        try {
            $this->process->mustRun();

            $this->info('The backup has been processed successfully.');
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process has failed.');
        }
    }
}
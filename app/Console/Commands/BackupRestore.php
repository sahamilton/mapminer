<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
Use Storage;

class BackupRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database backup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        //get all files in s3 storage backups directory
        $files = Storage::files('transfers');

        $i = 0;
        foreach ($files as $file) {

            $filename[$i]['file'] = $file;
            $i++;

        }

        $headers = ['File Name'];
        //output table of file to console
        $this->table($headers, $filename);
        //ask console user for input
        $backupFilename = $this->ask('Which file would you like to restore?');


        $getBackupFile  = Storage::disk('local')->get($backupFilename);

        $backupFilename  = explode("/", $backupFilename);
       
        Storage::disk('local')->put($backupFilename[1], $getBackupFile);
        //get file mime
        $mime = Storage::mimeType($backupFilename[1]);

        if ($mime == "application/x-gzip") {

            //mysql command to restore backup from the selected gzip file
            $command = "zcat " . storage_path() . "/" . $backupFilename[1] . " | mysql --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "";

        } elseif ($mime == "text/plain") {

            //mysql command to restore backup from the selected sql file
            $command = "mysql --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " < " . storage_path() . "/" . $backupFilename[1] . "";

        } else {

            //throw error if file type is not supported
            $this->error("File is not gzip or plain text");
            Storage::disk('local')->delete($backupFilename[1]);
            return false;

        }

        if ($this->confirm("Are you sure you want to restore the database? [y|N]")) {

            $returnVar  = null;
            $output     = null;
            exec($command, $output, $returnVar);

            Storage::disk('local')->delete($backupFilename
            );

            if (!$returnVar) {

                $this->info('Database Restored');

            } else {

                $this->error($returnVar);   

            }

        }
    }
}

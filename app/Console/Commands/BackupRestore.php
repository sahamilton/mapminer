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
        switch($mime) {

        case "application/x-gzip":
              $command = "zcat " . storage_path() . "/" . $backupFilename[1] . " | mysql --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "";

            break;
        case "application/zip":
            if ($this->_unZipFile($backupFilename)) {
                $ucfilename = str_replace(".zip", ".sql", $backupFilename[1]);
                $command = "mysql --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " < " . storage_path() . "/" . $ucfilename . "";
            } else {
                $this->error("Unzipp did not work");
            }
            break; 

        case "text/plain":

            //mysql command to restore backup from the selected sql file
            $command = "mysql --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " < " . storage_path() . "/" . $backupFilename[1] . "";
            break;

        default: 

            //throw error if file type is not supported
            $this->error("File is not gzip or plain text");
           // Storage::disk('local')->delete($backupFilename[1]);
            return false;
            break;
        }

        if ($this->confirm("Are you sure you want to restore the database? [y|N]")) {

            $returnVar  = null;
            $output     = null;
            exec($command, $output, $returnVar);

           // Storage::disk('local')->delete($backupFilename);

            if (!$returnVar) {

                $this->info('Database Restored');

            } else {

                $this->error($returnVar);   

            }

        }
    }

    private function _unZipFile($backupFilename)
    {

        $zip = new ZipArchive();

        if ($zip->open(storage_path('transfers')."/".$backupFilename[1]) === true) {
            $zip->extractTo(storage_path('transfers'));
            $zip->close();
            return true;
        } else {
            return false;
        }

    }
}

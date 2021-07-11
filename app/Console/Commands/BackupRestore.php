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
      
        //get lastest zip file in transfer directory
        $backupFilename = $this->_getLatestRestorableFile();
        
        if ($this->confirm("Are you sure you want to restore " . env('DB_DATABASE') . " from ".$backupFilename->getFilename()."? [y|N]")) {
            if (! $command = $this->_createRestoreCommand($backupFilename)) {
                $this->error('Unable to generate restore command');
            }
            $returnVar  = null;
            $output     = null;
            exec($command, $output, $returnVar);
            if (! $returnVar) {
                Storage::disk('local')->delete($backupFilename);
                $this->info('Database Restored');

            } else {

                $this->error($returnVar);   

            }

        }
        
        
    }
    /**
     * [unzip zip backup]
     * 
     * @param array $backupFilename [description]
     * 
     * @return [type]                 [description]
     */
    private function _unZipFile($backupFilename)
    {
        
        $zip = new \ZipArchive();

        if ($zip->open($backupFilename->getRealPath()) === true) {
            $zip->extractTo($backupFilename->getPath());
            $zip->close();
            return true;
        } else {
            return false;
        }

    }
    /**
     * [_createRestoreCommand description]
     * 
     * @param [type] $backupFilename [description]
     * 
     * @return [type]                 [description]
     */
    private function _createRestoreCommand($backupFilename)
    {
       
        switch($backupFilename->getExtension()) {

        case "gzip":
              $command = "zcat " . storage_path($backupFilename[0]) . "/" . $backupFilename[1] . " | mysql --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "";

            break;

        case "zip":
            if ($this->_unZipFile($backupFilename)) {
                // create sql filename
                $ucfilename = str_replace(".zip", ".sql", $backupFilename->getRealPath());
                $command = "mysql --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " < " .  $ucfilename . "";
            } else {
                $this->error("Unzip did not work");
                return false;
            }
            break; 

        case "sql":

            //mysql command to restore backup from the selected sql file
            $command = "mysql --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . " < " . $backupFilename->getRealPath()  . "";
            break;

        default: 

            //throw error if file type is not supported
            $this->error("File is not gzip or plain text");
            return false;
            break;
        }
        return $command;
    }
    /**
     * [_getRestorableFiles description]
     * 
     * @return [type] [description]
     */
    private function _getLatestRestorableFile()
    {
        return collect(\File::allFiles(storage_path('transfers')))
            ->filter(
                function ($file) {
                    return in_array($file->getExtension(), ['zip', 'sql']);
                }
            )
            ->sortByDesc(
                function ($file) {
                    return $file->getCTime();
                }
            )
            ->first();
    }
}

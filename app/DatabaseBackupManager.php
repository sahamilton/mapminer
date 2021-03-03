<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatabaseBackupManager extends Model
{
    public $backupDirectory = 'backups';

    public function allFiles()
    {
        return \File::files(storage_path($this->backupDirectory));
        //return \Storage::files($this->backupDirectory);
    }
}

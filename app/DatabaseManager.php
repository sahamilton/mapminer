<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatabaseManager extends Model
{
	public $backupDirectory = 'exports';

    public  function  allFiles()
    {
    	
    	return \File::files(storage_path($this->backupDirectory));
    	//return \Storage::files($this->backupDirectory);

    }
}

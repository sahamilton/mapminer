<?php

namespace App\Observers;
use App\OracleSource;
use App\Jobs\OracleImport;
class OracleSourceObserver
{
    public function created(OracleSource $source)
    {
        OracleImport::dispatch($source);
    }

}

<?php

namespace App\Observers;
use App\Models\OracleSource;
use App\Jobs\OracleImport;
class OracleSourceObserver
{
    public function created(OracleSource $source)
    {
        OracleImport::dispatch($source);
    }

}

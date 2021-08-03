<?php

namespace App\Imports;

use App\LocationImport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class LocationImport implements ToModel
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new LocationImport
    }
}

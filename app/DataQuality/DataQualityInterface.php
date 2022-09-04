<?php
namespace App\DataQuality;
use App\Models\Branch;

interface DataQualityInterface {
    
    public function count(Branch $branch);

    public function details(Branch $branch);
}
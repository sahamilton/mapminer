<?php
namespace App\DataQuality;
use App\Branch;

interface DataQualityInterface {
    
    public function count(Branch $branch);

    public function details(Branch $branch);
}
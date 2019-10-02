<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Address;
use App\Person;

class StaleLeadsExport implements FromView
{   
    public $address;
    public $manager;
    /**
     * [__construct description]
     * 
     * @param Array|null $branch [description]
     */
    public function __construct($addresses, $manager)
    {
        $this->addresses = $addresses;
        $this->manager = $manager;
        
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $addresses = $this->addresses->load('branch', 'address');
        $manager = $this->manager;
        return view('branchleads.flushed', compact('addresses', 'manager'));
    }
}
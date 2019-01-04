<?php

namespace App\Http\Controllers;

use App\OrderImport;
use App\Address;
use Illuminate\Http\Request;

class OrderImportController extends Controller
{
    public $import;
    public $address;
    public function __construct(OrderImport $import, Address $address){
        $this->import = $import;
        $this->address = $address;
    }

    public function index(){
        
       if ($this->import->count() >0) {

            return $this->import->storeOrders();
        }else{
            return redirect()->route('companies.importfile');
        }


    }

}

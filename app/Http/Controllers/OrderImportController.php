<?php

namespace App\Http\Controllers;

use App\OrderImport;
use App\Address;
use Illuminate\Http\Request;

class OrderImportController extends Controller
{
    public $import;
    public $address;
    public function __construct(OrderImport $import, Address $address)
    {
        $this->import = $import;
        $this->address = $address;
    }

    public function index()
    {
        
        if ($this->import->count() >0) {
          // post import routine
          // check if there are any companies to make
          // check if there are any addresses to merge
          // check if there are any contacts to add
            $data = $this->import->getImportUpdates();
            return response()->view('orders.import.cleanseimport', compact('data'));
        } else {
            return redirect()->route('companies.importfile');
        }
    }

    public function store(Request $request)
    {

     
        switch (request('type')) {
            case 'addresses';
                $this->import->matchAddresses($request);
            break;

            case 'companymatch';
                $this->import->matchCompanies($request);
            break;
            case 'contacts';

            break;
        }
        return redirect()->route('orderimport.index');
    }

    public function finalize()
    {
        $this->import->createMissingCompanies();
        $this->import->addNewAddresses();
        $this->import->storeOrders();
        return redirect()->route('orders.index');
    }

    public function flush()
    {
    }
}

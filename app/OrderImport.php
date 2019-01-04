<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderImport extends Model
{
    public $table = 'customerimport';

    public $fillable = ['account_id','company_id'];
     public function addAddressContacts(){
        // no idea how to do this
        // how do we deduplicate?
    }

    public function addNewAddresses(){
        $newAddresses = $this->import->whereNull('address_id')->select('id','businessname','lat','lng','street','address2','city','state','zip','customer_id')->distinct()->get();
        
        foreach ($newAddresses as $newaddress){
            $data = $newaddress->toArray();
            $data['addressabe_type'] = 'customer';
            
            $address = Address::create($data);
           
            $newaddress->address_id = $address->id;
            $newaddress->update(['address_id'=>$address->id]);
            }
        dd('all done');
    }

    public function storeOrders(){
        $orders = $this->import->whereNotNull('address_id')->select('address_id','branch_id','orders')->get();

        foreach ($orders as $order){
           
            $address = $this->address->whereId($order->address_id)->firstOrFail();
           
            $data = ['period'=>'201811','orders'=>$order->orders];         
         
            $address->orders()->syncWithoutDetaching
            ($order->branch_id,$data);
            
        }
    }

    public function missingCompanies(){
        if ($missing = $this->import->getCompaniesToCreate()){

            return response()->view('orders.import.createcompanies',compact('missing'));
        }
        return false;
    }

     public function matchedLocations(){
        if ($missing = $this->import->getCompaniesToCreate()){

            return response()->view('orders.import.createcompanies',compact('missing'));
        }
        return false;
    }


    public function createMissingCompanies(Request $request){
        // create company
        // update import with company id
    }

    public function updateMatchedLocations(Request $request){
        // update import with location ids

    }

}

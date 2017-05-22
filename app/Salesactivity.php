<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salesactivity extends Model
{
    public $table='salesactivity';
    public $fillable=['from','to','title'];
    public $dates = ['from','to'];

    public function salesprocess(){
    	return $this->belongsToMany(Salesprocess::class,'activity_process_vertical','activity_id','salesprocess_id')->withPivot('vertical_id');
    }

    public function vertical(){
    	return $this->belongsToMany(SearchFilter::class,'activity_process_vertical','activity_id','vertical_id')->withPivot('salesprocess_id');
    }
    


    /*public function relatedDocuments(){

    	return Document::whereHas('process',function($q) {
    		$q->where('id',$this->salesprocess_id);
    	})
    	whereHas('vertical',function($q) {
    		$q->where('id',$this->vertical_id);
    	})->get()
    }*/
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public $table='documents';

    public $fillable=['title','summary','user_id','link','description'];

    public function author(){
    	return $this->belongsTo(User::class, 'user_id','id')->with('person');
       
    }

    public function vertical(){
    	return $this->belongsToMany(SearchFilter::class,'document_searchfilter','document_id','searchfilter_id');

    }

    public function process(){
    	return $this->belongsToMany(SalesProcess::class,'document_salesprocess','document_id','salesprocess_id');
    }

     public function getDocumentsWithVerticalProcess($data){

        return $documents = $this->with('author','vertical','process')
                ->when($data['verticals'],function($q) use ($data){
                    $q->whereHas('vertical',function($q1) use ($data){
                        $q1->whereIn('id',$data['verticals']);
                    });
                 
                })        
                ->when($data['salesprocess'],function($q) use($data) {
                   
                    $q->whereHas('process',function($q1) use ($data) {
                        $q1->whereIn('id',$data['salesprocess']);
                    });
                })
                ->get();
        
    }
}

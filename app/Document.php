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

    /*
    
    Rank documents

     */
    public function rankings()
    {
        return $this->belongsToMany(User::class)->withPivot('rank');
    }

    public function myranking(){
        return $this->belongsToMany(User::class)->where('user_id','=',\Auth::user()->id)->withPivot('rank')->first();
    }

    public function rank()
    {
        return $this->rankings()
        ->selectRaw('document_id, avg(rank) as rank')
        ->groupBy('document_id');

    
    }
    public function score()
    {
        return $this->rankings()
        ->selectRaw('document_id, sum(rank) as score')
        ->groupBy('document_id');

    
    }

    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}

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
}

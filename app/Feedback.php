<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public $fillable = ['url','user_id','feedback','type','biz_rating','tech_rating'];

    public function providedBy(){
    	return $this->belongsTo(User::class,'user_id')->with('person');
    }
    public function category(){
    	return $this->belongsTo(FeedbackCategory::class,'type','id');
    }
}

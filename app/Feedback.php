<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public $fillable = ['url','user_id','feedback','type','biz_rating','tech_rating','status'];

    public function providedBy()
    {
        return $this->belongsTo(User::class, 'user_id')->with('person')->withDefault('No longer with the company');
    }
    public function category()
    {
        return $this->belongsTo(FeedbackCategory::class, 'type', 'id');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', '=', 'closed');
    }
    public function scopeOpen($query)
    {
         return $query->where('status', '=', 'open');
    }

    public function comments()
    {
        return $this->hasMany(FeedbackComments::class);
    }
}

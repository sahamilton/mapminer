<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeedbackComments extends Model
{
    public $fillable = ['user_id','comment','feedback_id'];

    public function feedback()
    {
        return $this->belongsTo(Feedback::class);
    }

    public function by()
    {
        return $this->belongsTo(User::class, 'user_id')->with('person');
    }
}

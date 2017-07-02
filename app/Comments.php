<?php
namespace App;
class Comments extends Model {

	// Add your validation rules here
	public $rules = [

	 'comment' => 'required'
	];
	protected $table ='comments';

	// Don't forget to fill this array
	protected $fillable = ['subject','title','comment','user_id','url_from','comment_status','news_id'];
	
	public function postedBy () {
		
		return $this->belongsTo(User::class,'user_id');

	}

	public function relatesTo(){
		return $this->belongsTo(News::class,'news_id');
	}

	public function close($id) 
	{
		$comment = $this->model->findOrFail($id);
		$comment->status ='closed';
		$commnet->save;	
	}
}
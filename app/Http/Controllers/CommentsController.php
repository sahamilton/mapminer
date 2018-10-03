<?php
namespace App\Http\Controllers;
use App\Comments;
use App\Http\Requests\CommentFormRequest;
use Mail;
use App\User;
use App\Mail\NotifyCommentsAdded;

class CommentsController extends BaseController {

	/**
	 * Display a listing of People
	 *
	 * @return Response
	 */
	public $comment;
	
	public function __construct(Comments $comment) {
		
		$this->comment = $comment;
		parent::__construct($comment);
	}
	
	
	
	public function index()
	{
		$comments = $this->comment->with('postedBy')->orderBy('created_at','ASC')->get();


		return response()->view('comments.index', compact('comments','fields'));
	}

	/**
	 * Show the form for creating a new Comment
	 *
	 * @return Response
	 */
	public function create()
	{
		return response()->view('comments.create');
	}

	/**
	 * Store a newly created Comment in storage.
	 *
	 * @return Response
	 */
	public function store(CommentFormRequest $request)
	{
		$data = request()->all();
		$data['subject'] = request('title');
		$data['title'] = request('slug');
		$data['user_id'] = auth()->user()->id;
		$data = $this->comment->create($data);
		$this->notify($data);
		return redirect()->route('news.index');
	}

	/**
	 * Display the specified Comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
	
		$people = $this->comment->with('manages')->findorFail($id->id);
		return response()->view('comments.showlist', compact('comment'));
	}
	
	/**
	 * Mark specified Comment as closed.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function close($id)
	{
		
		$comment = $this->comment->findOrFail($id);
		
		$comment->comment_status ='closed';
		
		$comment->save();

		$comments = $this->comment->all();	
		return response()->view('comments.index', compact('comments'));
		
			
	}

	

	/**
	 * Show the form for editing the specified Comment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
			
			$comment = $this->comment->with('relatesTo','postedBy')->findOrFail($id);
			return response()->view('comments.edit', compact('comment'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(CommentFormRequest $request,$id)
	{
		$comment = $this->comment->findOrFail($id);
		$comment->update(request()->all());

		return redirect()->route('news.show',request('slug'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$comment = $this->comment->findOrFail($id);
		$this->comment->destroy($id);

		return redirect()->route('news.show',$comment->relatesTo->slug);
	}


	public function download()
	{
		
		Excel::create('Comments',function($excel){
			$excel->sheet('Comments',function($sheet) {
				$comments = $this->comment->orderBy('created_at','ASC')->get();
				$sheet->loadview('comments.export',compact('comments'));
			});
		})->download('csv');

	}
	
	
	
	private function notify($comments){
		$data = array();
		$data['comments'] =  $comments;

		$data['user'] = User::with('person')->findOrFail($comments['user_id']);
		
		Mail::queue(new NotifyCommentsAdded($data));
	}
	
}
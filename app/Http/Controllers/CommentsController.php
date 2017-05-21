<?php
namespace App\Http\Controllers;
use App\Comments;
use App\Http\Requests\CommentFormRequest;


class CommentsController extends BaseController {

	/**
	 * Display a listing of People
	 *
	 * @return Response
	 */
	public $comment;
	
	public function __construct(Comments $comment) {
		
		$this->comment = $comment;
		parent::__construct();
	}
	
	
	
	public function index()
	{
		$comments = Comments::with('postedBy')->orderBy('created_at','ASC')->get();


		$fields = array('Date'=>'created_at',
				'Subject'=>'subject',
				'Title'=>'title',
				'Feedback'=>'comment',
				'Status'=>'comment_status',
				'Posted By'=>'user_id');
				
	if (\Auth::user()->hasRole('Admin')) {
			$fields['Actions']='actions';
		}
		
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
		$data = $request->all();
	
		$data['title'] = $request->get('slug');
		$data['user_id'] = \Auth::user()->id;
		$data = Comments::create($data);
		if (\App::environment() == 'production') 
		{
			$this->notify($data);
		}
		
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
		
		$people = Comments::with('manages')->findorFail($id->id);
		return response()->view('comments.showlist', compact('people'));
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
	public function edit($comment)
	{
			return response()->view('comments.edit', compact('comment'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($comment)
	{
		
		$validator = Validator::make($data = \Input::all(), $this->comment->rules);

		if ($validator->fails())
		{
			return \Redirect::back()->withErrors($validator)->withInput();
		}

		$comment->update($data);

		return \Redirect::route('comment.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Comments::destroy($id);

		return \Redirect::route('comment.index');
	}


	public function download()
	{
		
		$filename = "attachment; filename=\"feedback.csv\"";
		$comments = $this->comment->orderBy('created_at','ASC')->get();
		$fields = array('id','created_at','subject','title','comment','comment_status','user');
		
		$results = $this->comment->export($fields,$comments,'Feedback');
		
 	 	return Response::make(rtrim($results['output'], "\n"), 200, $results['headers']);
	}
	
	
	
	private function notify($comments){
		$data = array();
		$data['comments'] =  $comments;
		$data['user'] = User::findOrFail($comments['user_id']);
		
		
		Mail::send('emails.newcomment',$data, function($message)
		{
			$message->to('tbsupport@crescentcreative.com')->subject('New Comment Added');
			
		});
		
	}
	
}
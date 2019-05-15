<?php
namespace App\Http\Controllers;

use App\Comments;
use App\Http\Requests\CommentFormRequest;
use Mail;
use App\User;
use App\Mail\NotifyCommentsAdded;

class CommentsController extends BaseController
{

    /**
     * Display a listing of People
     *
     * @return Response
     */
    public $comment;
    
    /**
     * [__construct description]
     * 
     * @param Comments $comment [description]
     */
    public function __construct(Comments $comment)
    {
        
        $this->comment = $comment;
        parent::__construct($comment);
    }
    
    
    /**
     * [index description]
     * 
     * @return [type] [description]
     */
    public function index()
    {
        $comments = $this->comment->with('postedBy', 'postedBy.person')
            ->orderBy('created_at', 'ASC')
            ->get();


        return response()->view('comments.index', compact('comments', 'fields'));
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
     * [store description]
     * 
     * @param CommentFormRequest $request [description]
     * 
     * @return [type]                      [description]
     */
    public function store(CommentFormRequest $request)
    {
        
        $data = request()->all();
        $data['user_id'] = auth()->user()->id;
        $data = $this->comment->create($data);
        $this->_notify($data);
        return redirect()->route('news.index');
    }

    /**
     * Display the specified Comment.
     *
     * @param int $id [description]
     * 
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
     * @param int $id [description]
     * 
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
     * @param int $id [descripiton]
     * 
     * @return Response
     */
    public function edit($id)
    {
            
            $comment = $this->comment->with('relatesTo', 'postedBy')
                ->findOrFail($id);
            return response()->view('comments.edit', compact('comment'));
    }

    /**
     * [update description]
     * 
     * @param CommentFormRequest $request [description]
     * @param [type]             $id      [description]
     * 
     * @return [type]                      [description]
     */
    public function update(CommentFormRequest $request, $id)
    {
        $comment = $this->comment->findOrFail($id);

        $comment->update(request()->all());

        return redirect()->route('news.show', request('slug'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id [description]
     * 
     * @return Response
     */
    public function destroy($id)
    {
        $comment = $this->comment->findOrFail($id);
        $this->comment->destroy($id);

        return redirect()->route('news.show', $comment->relatesTo->slug);
    }

    /**
     * [download description]
     * 
     * @return [type] [description]
     */
    public function download()
    {
        
        Excel::download(
            'Comments', function ($excel) {
                $excel->sheet(
                    'Comments', function ($sheet) {
                        $comments = $this->comment->orderBy('created_at', 'ASC')
                            ->get();
                        $sheet->loadview('comments.export', compact('comments'));
                    }
                );
            }
        )->download('csv');
    }
    
    
    /**
     * [_notify description]
     * 
     * @param [type] $comment [description]
     * 
     * @return [type]          [description]
     */
    private function _notify($comment)
    {
        
        $comment->load('postedBy');

        
        Mail::queue(new NotifyCommentsAdded($comment));
    }
}

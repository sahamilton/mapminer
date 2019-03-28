<?php

namespace App\Http\Controllers;

use Mail;
use App\Mail\FeedbackClosed;
use App\Mail\FeedbackComment;
use App\Feedback;
use App\FeedbackComments;
use App\Events\FeedbackEvent;
use Illuminate\Http\Request;

class FeedbackCommentsController extends Controller
{
    public $comments;
    public $feedback;

    public function __construct(Feedback $feedback, FeedbackComments $comments){
        $this->comments = $comments;
        $this->feedback = $feedback;

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $feedback = $this->feedback->with('providedBy','comments')->findOrFail(request('feedback_id'));
        $data = request()->except('_token');
        $data['user_id'] = auth()->user()->id;
        $feedback->comments()->create($data);
        $feedback->load('comments');
        if(request()->filled('close')){
            $feedback->update(['status'=>'closed']);
        }
        event(new FeedbackEvent($feedback));
        return redirect()->route('feedback.show',$feedback->id)->withMessage('Thanks for commenting on this feedback');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FeedbackComments  $feedbackComments
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeedbackComments $comments)
    {
        
        $comments->delete();
        return redirect()->back()->withMessage("Comment deleted");
    }
}

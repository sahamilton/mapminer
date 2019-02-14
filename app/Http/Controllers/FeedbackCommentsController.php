<?php

namespace App\Http\Controllers;

use Mail;
use App\Mail\FeedbackClosed;
use App\Feedback;
use App\FeedbackComments;
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
     
        $feedback = $this->feedback->findOrFail(request('feedback_id'));
        $data = request()->except('_token');
        $data['user_id'] = auth()->user()->id;
        $feedback->comments()->create($data);
        if(request()->filled('close')){
            $feedback->update(['status'=>'closed']);
            Mail::to(config('mapminer.system_contact'))
                ->cc(config('mapminer.developer_email'))
                ->send(new FeedbackClosed($feedback));
        }
        return redirect()->route('feedback.show',$feedback->id)->withMessage('Thanks for commenting on this feedback');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FeedbackComments  $feedbackComments
     * @return \Illuminate\Http\Response
     */
    public function edit(FeedbackComments $feedbackComments)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FeedbackComments  $feedbackComments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeedbackComments $feedbackComments)
    {
        //
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

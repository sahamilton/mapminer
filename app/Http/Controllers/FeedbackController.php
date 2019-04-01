<?php

namespace App\Http\Controllers;

use Mail;
use App\Feedback;
use Illuminate\Http\Request;
use App\Mail\FeedBackResponseEmail;
use App\Mail\FeedbackClosed;
use App\Mail\FeedbackOpened;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FeedbackExport;
use App\Http\Requests\FeedbackFormRequest;

class FeedbackController extends Controller
{
    public $feedback;

    public function __construct(Feedback $feedback)
    {

        $this->feedback = $feedback;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedback_open = $this->feedback->with('providedBy','category')
        ->open()
        ->withCount('comments');
        $feedback_closed = $this->feedback->with('providedBy','category')
        ->closed()
        ->withCount('comments');

        if(! auth()->user()->hasRole('admin')){
            $feedback_open = $feedback_open->where('user_id','=',auth()->user()->id)->get();
            $feedback_closed = $feedback_closed->where('user_id','=',auth()->user()->id)->get();
            return response()->view('feedback.users',compact('feedback_open','feedback_closed'));
        }


        $feedback_open = $feedback_open->get();
        $feedback_closed = $feedback_closed->get();
        return response()->view('feedback.index',compact('feedback_open','feedback_closed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('feedback.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        $data = request()->except('_token');
        $data['user_id'] = auth()->user()->id;
        $feedback = $this->feedback->create($data);
        
       
        if (auth()->user()->hasRole(['admin','sales_operations'])) {
            return redirect()->route('feedback.index')->withMessage("Feedback entered");
        } else {
            $feedback->load('providedBy', 'category');
            
            Mail::queue(new FeedBackResponseEmail($feedback));
        
            return redirect()->back()->withMessage("Thanks,". $feedback->providedBy->person->firstname. " for your feedback");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('providedBy', 'category', 'comments', 'comments.by');
        return response()->view('feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(Feedback $feedback)
    {
         $feedback->load('providedBy', 'category');
         return response()->view('feedback.edit', compact('feedback'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function update(FeedbackFormRequest $request, Feedback $feedback)
    {
        
        $feedback->update(request()->except('_token'));
        return redirect()->route('feedback.index')->withMessage('Feedback updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return redirect()->route('feedback.index')->withMessage('Feedback deleted');
    }

    public function close(Feedback $feedback)
    {
        $feedback->update(['status'=>'closed']);
        $feedback->load('comments');
        Mail::to(config('mapminer.system_contact'))
        ->cc(config('mapminer.developer_email'))
        ->send(new FeedbackClosed($feedback));

        return redirect()->back()->withMessage('Feedback closed');
    }

    public function open(Feedback $feedback)
    {
        $feedback->update(['status'=>'open']);
        $feedback->load('comments');
        Mail::to(config('mapminer.system_contact'))
        ->cc(config('mapminer.developer_email'))
        ->send(new FeedbackOpened($feedback));

        return redirect()->route('feedback.show', $feedback->id)->withMessage('Feedback reopened. Add a comment');
    }

    public function export()
    {

        return Excel::download(new FeedbackExport(), 'AllFeedback.csv');
    }
}

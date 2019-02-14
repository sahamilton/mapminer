<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;
use App\Mail\FeedBackResponseEmail;
use Mail;
use App\Http\Requests\FeedbackFormRequest;
class FeedbackController extends Controller
{
    public $feedback;

    public function __construct(Feedback $feedback){

        $this->feedback = $feedback;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $feedback = $this->feedback->with('providedBy','category')->get();
        return response()->view('feedback.index',compact('feedback'));
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
    public function store(FeedbackFormRequest $request)
    {
        
        $data = request()->except('_token');
        $data['user_id'] = auth()->user()->id;
        $feedback = $this->feedback->create($data);
        $feedback->load('providedBy','category');

        // send email reply
        Mail::queue(new FeedBackResponseEmail($feedback));
        // forward email
        return redirect()->back()->withMessage("Thanks,". $feedback->providedBy->person->firstname. " for your feedback");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback $feedback)
    {
        $feedback->load('providedBy','category');
        return response()->view('feedback.show',compact('feedback'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function edit(Feedback $feedback)
    {
         $feedback->load('providedBy','category');
         return response()->view('feedback.edit',compact('feedback'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feedback  $feedback
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feedback $feedback)
    {
        //
    }
}

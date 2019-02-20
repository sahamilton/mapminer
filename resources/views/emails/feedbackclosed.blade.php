@component('mail::message')

## Feedback Closed

{{$user->person->fullName()}} has closed a feedback:


@component('mail::panel')
Type: {{$feedback->category->category}}

Page Ref: {{$feedback->url}}

Feedback: {{$feedback->feedback}}

Date: {{$feedback->updated_at->format('F js, Y')}}

Originally posted By: {{$feedback->providedBy->person->fullName()}}

Last Comment: {{$feedback->comments->last}}

@endcomponent
@component('mail::button', ['url' => route('feedback.show',$feedback->id), 'color' => 'blue'])
        You can see details at  {{route('feedback.show',$feedback->id)}}.
@endcomponent


Sincerely
        
{{env('APP_NAME')}}
@endcomponent

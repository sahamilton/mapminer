@component('mail::message')

## Feedback Reopened

{{$user->person->fullName()}} has reopened a feedback:


@component('mail::panel')
Type: {{$feedback->category->category}}

Page Ref: {{$feedback->url}}

Feedback: {{$feedback->feedback}}

Date: {{$feedback->updated_at->format('F js, Y')}}

Originally posted By: {{$feedback->providedBy->person->fullName()}}

@if($feedback->comments->count()>0)

Last Comment: {{$feedback->comments->last()->comment}}

@endif

@endcomponent

@component('mail::button', ['url' => route('feedback.show',$feedback->id), 'color' => 'blue'])
        You can see details at  <a href="{{route('feedback.show',$feedback->id)}}">this link</a>.
@endcomponent


Sincerely
        
{{env('APP_NAME')}}
@endcomponent

@component('mail::message')

## Feedback Closed

{{$user->person->fullName()}} has closed a feedback:


@component('mail::panel')
Type: {{$feedback->category->category}}

Page Ref: {{$feedback->url}}

Feedback: {{$feedback->feedback}}

Date: {{$feedback->updated_at->format('F jS, Y')}}

Originally posted By: {{$feedback->providedBy->person->fullName()}}

@if($feedback->comments->count()>0)

Last Comment: {{$feedback->comments->last()['comment']}}

@endif

@endcomponent

You can see details at  <a href="{{route('feedback.show',$feedback->id)}}">this link</a>.

Sincerely
        
{{env('APP_NAME')}}
@endcomponent

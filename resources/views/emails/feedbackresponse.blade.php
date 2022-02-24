@component('mail::message')

## Thanks for Your Feedback

Thanks {{$feedback->providedBy->person->firstname}} for posting feedback on {{config('app.name')}}. We have forwarded your feedback onto the appropriate people.


@component('mail::panel')
Type: {{$feedback->category->category}}

Page Ref: {{$feedback->url}}

Feedback: {{$feedback->feedback}}

Date: {{$feedback->created_at->format('F jS, Y')}}

Posted By: {{$feedback->providedBy->person->fullName()}}

Email: {{$feedback->providedBy->email}}


@endcomponent


Sincerely
        
{{env('APP_NAME')}}
@endcomponent

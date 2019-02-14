@component('mail::message')

## Thanks for Your Feedback

Thanks {{$feedback->providedBy->person->firstname}} for posting feedback on {{config('app.app_name')}}. We have forwarded your feeback onto the appropriate people.


@component('mail::panel')
Type: {{$feedback->category->category}}

Page Ref: {{$feedback->url}}

Feedback: {{$feedback->feedback}}

Date: {{$feedback->created_at->format('F js, Y')}}

Posted By: {{$feedback->providedBy->person->fullName()}}

@endcomponent


Sincerely
        
{{env('APP_NAME')}}
@endcomponent

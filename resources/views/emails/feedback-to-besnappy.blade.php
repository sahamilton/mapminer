@component('mail::message')
# New Mapminer Feedback

@component('mail::panel')
Posted By: {{$feedback->providedBy->person->fullName()}}

Email: {{$feedback->providedBy->email}}

Type: {{$feedback->category->category}}

Page Ref: {{$feedback->url}}

Feedback: {{$feedback->feedback}}

Date: {{$feedback->created_at->format('F jS, Y')}}

@endcomponent


{{ config('app.name') }}
@endcomponent

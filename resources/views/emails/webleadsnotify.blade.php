@component('mail::message')

## New Web Lead 

{{$person->firstname}}, 

You have been assigned a new lead that came through the People Ready website.  The details are below:

@component('mail::panel')

**Company Details**

Company: {{$lead->company_name}}

Address: {{$lead->address}}{{$lead->city}}, {{$lead->state}}

Contact: {{$lead->first_name}} {{$lead->last_name}}

Contact Title: {{$lead->contactitle}}

Phone: {{$lead->phone_number}}

Email: {{$lead->email_address}}

**Job Requirements**

Jobs:{{$lead->jobs}}

Time Frame:{{$lead->time_frame}}

Industry:{{$lead->industry}}

@endcomponent

@component('mail::button', ['url' => route('my.webleads'), 'color' => 'blue'])
        Check out your sales prospects and resources.
@endcomponent

Note that the nearest branch to this lead is {{$branch->branchname}}.

Sincerely
        
{{env('APP_NAME')}}
@endcomponent
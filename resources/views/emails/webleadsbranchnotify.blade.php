@component('mail::message')

## New Web Lead 

{{$manager->firstname}}, 


A new lead that came through the People Ready website has been assigned to {{$lead->salesteam->first()->postName()}} and branch {{$branch->branchname}}.  The details of this lead are below:

@component('mail::panel')

**Company Details**

Company: {{$lead->company_name}}

Address: {{$lead->address->address}}{{$lead->address->city}}, {{$lead->address->state}}

Contact: {{$lead->first_name}} {{$lead->last_name}}

Contact Title: {{$lead->contactitle}}

Phone: {{$lead->phone_number}}

Email: {{$lead->email_address}}

**Job Requirements**

Jobs:{{$lead->jobs}}

Time Frame:{{$lead->time_frame}}

Industry:{{$lead->industry}}

@endcomponent

@component('mail::button', ['url' => route('salesleads.index'), 'color' => 'blue'])
        Check out your sales prospects and resources.
@endcomponent


Sincerely
        
{{env('APP_NAME')}}
@endcomponent
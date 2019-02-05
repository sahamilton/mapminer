@component('mail::message')

## New Web Lead 

{{$manager->firstname}}, 


A new lead has been assigned to branch {{$branch->branchname}}.  The details of this lead are below:

@component('mail::panel')

**Company Details**

Company: {{$lead->businessname}}

Address: {{$lead->fullAddress()}}

Contact: {{$lead->contacts->first()->fullname}}

Contact Title: {{$lead->contacts->first()->title}}

Phone: {{$lead->contacts->first()->phone}}

Email: {{$lead->contacts->first()->email}}

**Job Requirements**

Jobs:{{$lead->jobs}}

Time Frame:{{$lead->time_frame}}

Industry:{{$lead->industry}}

@endcomponent

@component('mail::button', ['url' => route('address.show',$lead->id), 'color' => 'blue'])
        Check out your sales prospects and resources.
@endcomponent


Sincerely
        
{{env('APP_NAME')}}
@endcomponent
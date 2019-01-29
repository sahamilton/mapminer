@component('mail::message')

## New Web Lead 

{{$manager->firstname}}, 


A new lead that came through the People Ready website has been assigned to branch {{$branch->branchname}}.  The details of this lead are below:

@component('mail::panel')

**Company Details**

Company: {{$lead->businessname}}

Address: {{$lead->fullAddress()}}

Contact: {{$lead->contact->fullname}}

Contact Title: {{$lead->contact->title}}

Phone: {{$lead->contact->phone}}

Email: {{$lead->contact->email}}

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
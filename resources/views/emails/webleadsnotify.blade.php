@component('mail::message')

## New Lead 

{{$person->firstname}}, 

You have been assigned a new lead from the {{$lead->leadsource->source}}.  The details are below:

@component('mail::panel')

**Company Details**

Company: {{$lead->companyname}}

Address: {{$lead->address}} {{$lead->city}}, {{$lead->state}}
@foreach ($lead->contact as $contact)
Contact: {{$contact->firstname}} {{$contact->lastname}}

Contact Title: {{$contact->title}}

Phone: {{$contact->phone}}

Email: {{$contact->email}}
@endforeach

@endcomponent

@component('mail::button', ['url' => route('salesrep.newleads',$person->id), 'color' => 'blue'])
        Check out your sales prospects and resources.
@endcomponent

Note that the nearest branch to this lead is {{$branch->branchname}}.

Sincerely
        
{{env('APP_NAME')}}
@endcomponent
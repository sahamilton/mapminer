@component('mail::message')

## New Lead 

{{$person->firstname}}, 
{{dd('weblead', $lead,$person)}}
You have been assigned a new lead from the {{$lead->leadsource->source}}.  The details are below:

@component('mail::panel')

**Company Details**

Company: {{$lead->companyname}}

Address: {{$lead->address}} {{$lead->city}}, {{$lead->state}}

Contact: {{$lead->contacts->contact}}

Contact Title: {{$lead->contacts->contactitle}}

Phone: {{$lead->contacts->contactphone}}

Email: {{$lead->contacts->contactemail}}

@endcomponent

@component('mail::button', ['url' => route('salesrep.newleads',$person->id), 'color' => 'blue'])
        Check out your sales prospects and resources.
@endcomponent

Note that the nearest branch to this lead is {{$branch->branchname}}.

Sincerely
        
{{env('APP_NAME')}}
@endcomponent
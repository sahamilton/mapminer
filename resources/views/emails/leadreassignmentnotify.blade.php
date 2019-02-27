@component('mail::message')

## Lead Reassigned

{{$person->firstname}}, 

{{auth()->user()->person->fullName()}} has reassinged a lead to the {{$branch->branchname}} branch.  The details are below:

@component('mail::panel')

**Address Details**

Company: {{$address->businessname}}

Address: {{$address->fullAddress()}}


@endcomponent

@component('mail::button', ['url' => route('address.show',$address->id), 'color' => 'blue'])
       Check out this lead.
@endcomponent

You can contact {{auth()->user()->person->firstname}}  at <a href="mailto:{{auth()->user()->email}}">{{auth()->user()->email}}</a> for more information.

Sincerely
        
{{env('APP_NAME')}}
@endcomponent
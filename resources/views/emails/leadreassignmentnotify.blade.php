@component('mail::message')

## Lead Reassigned

{{$person->firstname}}, 

{{$sender->fullName()}} has reassigned a lead to the {{$branch->branchname}} branch.  The details are below:

@component('mail::panel')

**Address Details**

Company: {{$address->businessname}}

Address: {{$address->fullAddress()}}


@endcomponent

@component('mail::button', ['url' => route('address.show',$address->id), 'color' => 'blue'])
       Check out this lead.
@endcomponent

You can contact {{$sender->firstname}}  at <a href="mailto:{{$sender->userdetails->email}}">{{$sender->userdetails->email}}</a> for more information.

Sincerely
        
{{env('APP_NAME')}}
@endcomponent

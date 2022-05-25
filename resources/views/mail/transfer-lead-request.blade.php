@component('mail::message')
# Transfer Lead Request

Hi {{$address->claimedByBranch->first()->manager()->first()->firstname}}

I would like you to transfer your lead: 

@component('mail::panel')

**Address Details**

Company: <a href="{{route('address.show', $address->id)}}">{{$address->businessname}}</a>

Address: {{$address->fullAddress()}}


@endcomponent

from branch {{$address->claimedByBranch->first()->branchname}} 

to our branch {{$user->person->branchesServiced()->first()->branchname}} as we are servicing this client.

@component('mail::button', ['url' => route('address.show', $address->id),'color'=>'blue'])
Review Lead
@endcomponent

You can contact me at <a href="mailto:{{$user->email}}">{{$user->email}}</a> for more information.

Thanks,<br>
{{ $user->person->fullName()}}
@endcomponent

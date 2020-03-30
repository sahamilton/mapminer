@component('mail::table')
Business | Address | City | State | ZIP |
------------- |------------- |------------- |------------- |------------- |
@foreach ($branch->offeredLeads as $lead)
@if($loop->iteration < 11)
|[{{$lead->businessname}}](https://mapminer.test/address/{{$lead->id}})| {{$lead->street}} | {{$lead->city}} | {{$lead->state}} | {{$lead->zip}} |
@endif
@endforeach
@endcomponent

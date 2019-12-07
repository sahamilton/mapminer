@component('mail::table')
Business | Address | City | State | ZIP |
------------- |------------- |------------- |------------- |------------- |
@foreach ($branch->offeredLeads as $lead)
@if($loop->iteration < 11)
|[Fred](https://mapminer.test)| {{$lead->street}} | {{$lead->city}} | {{$lead->state}} | {{$lead->zip}} |
@endif
@endforeach
@endcomponent

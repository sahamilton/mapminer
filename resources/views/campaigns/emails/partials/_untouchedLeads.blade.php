@component('mail::table')
Business|
Address|
City|
State|
ZIP|
------------- |------------- |------------- |------------- |------------- |
@foreach ($branch->untouchedLeads as $lead)
|[{{$lead->businessname}}]({!! route('address.show', $lead->id)!!})|{{$lead->street}}|{{$lead->city}}|{{$lead->state}}|{{$lead->zip}}|
@endforeach
@endcomponent


@component('mail::table')
Business | Address| City| State | ZIP|
------------- |------------- |------------- |------------- |------------- |
@foreach ($data->workedLeads as $lead)
|[{{$lead->businessname}}]({!! route('address.show', $lead->id)!!})|{{$lead->street}}|{{$lead->city}}|{{$lead->state}}|{{$lead->zip}}|
@endforeach
@endcomponent

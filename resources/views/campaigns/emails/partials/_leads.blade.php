@component('mail::table')
Business | Address| City| State | ZIP|
------------- |------------- |------------- |------------- |------------- |
@foreach ($branch->leads as $lead)
|[{{$lead->businessname}}]({!! route('address.show', $lead->id)!!})|{{$lead->street}}|{{$lead->city}}|{{$lead->state}}|{{$lead->zip}}|
@endforeach
@endcomponent

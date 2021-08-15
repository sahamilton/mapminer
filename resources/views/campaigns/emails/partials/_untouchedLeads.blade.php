@component('mail::table')
|Business| Address| City |
|------------- |------------- |------------- |
@foreach ($data->untouchedleads as $lead)
|[{{$lead->businessname}}]({!! route('address.show', $lead->id)!!})|{{$lead->street}}|{{$lead->city}}|
@endforeach
@endcomponent

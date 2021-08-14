@component('mail::table')
|Business| Address| City |
|------------- |------------- |------------- |
@foreach ($data->untouchedLeads as $lead)
|[{{$lead->businessname}}]({!! route('address.show', $lead->id)!!})|{{$lead->street}}|{{$lead->city}}|
@endforeach
@endcomponent

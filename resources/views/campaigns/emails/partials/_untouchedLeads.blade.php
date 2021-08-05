@component('mail::table')
|Business| Address| City |
|------------- |------------- |------------- |
@foreach ($branch->untouchedLeads as $lead)
|[{{$lead->businessname}}]({!! route('address.show', $lead->id)!!})|{{$lead->street}}|{{$lead->city}}|
@endforeach
@endcomponent

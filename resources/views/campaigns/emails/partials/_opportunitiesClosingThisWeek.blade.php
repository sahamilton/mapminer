@php
$statuses = ['0'=>'Open', '1'=>"Closed Won", '2'=>'Closed Lost']
@endphp
@component('mail::table')
| Title | Company |Potential $$ |
|------------- |------------- |------------- |
@foreach ($data->opportunitiesClosingThisWeek as $opportunity)
 {!!$opportunity->title!!}|[{{$opportunity->address->address->businessname}}]({!!route('address.show',$opportunity->address->address->id)!!})|${{number_format($opportunity->value,0)}}|
@endforeach
@endcomponent

@php
$statuses = ['0'=>'Open', '1'=>"Closed Won", '2'=>'Closed Lost']
@endphp
@component('mail::table')
|Title|
|Date Opened|
|Days Open|
|Company|
|Address|
|Potential $$|
|Last Activity|
|------------- |------------- |------------- |------------- |------------- |------------- |------------- |
@foreach ($branch->opportunitiesClosingThisWeek as $opportunity)
 [{!! $opportunity->title ?  $opportunity->title : $opportunity->id !!}]({{route('opportunity.show',$opportunity->id)}})| {!! $opportunity->created_at ? $opportunity->created_at->format('Y-m-d') : ''!!}|{{$opportunity->daysOpen()}}|[{{$opportunity->address->address->businessname}}]({!!route('address.show',$opportunity->address->address->id)!!})|{{$opportunity->address->address->fullAddress()}}|${{number_format($opportunity->value,2)}}|          @if($opportunity->address->activities->count() >0 ) {{$opportunity->address->activities->where('completed',1)->last()->activity_date->format('Y-m-d')}} @endif
@endforeach
@endcomponent

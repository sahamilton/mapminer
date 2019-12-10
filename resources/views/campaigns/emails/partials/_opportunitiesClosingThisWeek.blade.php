@php
$statuses = ['0'=>'Open', '1'=>"Closed Won", '2'=>'Closed Lost']
@endphp
@component('mail::table')
|Title
|Company
|Address
|Potential $$|
|------------- |------------- |------------- |------------- |
@foreach ($branch->opportunitiesClosingThisWeek as $opportunity)
 [{!! $opportunity->title ?  $opportunity->title : $opportunity->id !!}]({{route('opportunity.show',$opportunity->id)}})|[{{$opportunity->address->address->businessname}}]({!!route('address.show',$opportunity->address->address->id)!!})|{{$opportunity->address->address->fullAddress()}}|${{number_format($opportunity->value,2)}}|
@endforeach
@endcomponent

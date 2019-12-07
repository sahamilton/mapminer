@component('mail::table')
|Date|Company|Activity|
|------------- |------------- |------------- |------------- |------------- |------------- |
@foreach ($branch->openActivities as $activity)
|{!! $activity->activity_date ? $activity->activity_date->format('Y-m-d'):''!!}|[{{$activity->relatesToAddress->businessname}}]({!!route('address.show', $activity->address_id)!!}) | @if($activity->type)     {{$activity->type->activity}} @endif |
@endforeach
@endcomponent
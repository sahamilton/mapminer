@component('mail::table')
|Date|Company|Activity|
|------------- |------------- |------------- |
@foreach ($data->upcomingActivities as $activity)
|@if($activity->activity_date) {{$activity->activity_date->format('Y-m-d')}} @endif |[{{$activity->relatesToAddress->businessname}}]({!! route('activity.show', $activity->id)!!}) | @if($activity->type)     {{$activity->type->activity}} @endif |
@endforeach
@endcomponent
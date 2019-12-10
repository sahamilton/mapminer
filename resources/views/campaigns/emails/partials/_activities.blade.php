@component('mail::table')
|Date
|Company
|Activity
|Contact
|Notes
|Status
|------------- |------------- |------------- |------------- |------------- |------------- |
@foreach ($branch->activities as $activity)
|{!! $activity->activity_date ? $activity->activity_date->format('Y-m-d'):''!!}|[{{$activity->relatesToAddress->businessname}}]({!!route('address.show', $activity->address_id)!!}) | @if($activity->type)     {{$activity->type->activity}} @endif |                |@foreach($activity->relatedContact as $contact) * {{$contact->fullname}} @endforeach |{{$activity->note}}|{!! $activity->completed ? 'Completed' : '' !!}|
@endforeach
@endcomponent
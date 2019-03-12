@component('mail::message')

## Your Upcoming Activities

{{$user->person->firstname}} 

You have the following activities coming up in the next seven days.

@component('mail::table')
Company      	| Follow Up Date  |
| ------------- | --------:|
@foreach ($user->activities as $activity)
| <a href="{{route('address.show',$activity->relatesToAddress->id)}}">{{$activity->relatesToAddress->businessname}} </a>|  @if($activity->followup_date) {{$activity->followup_date->format('D jS M')}} @endif | 
@endforeach

@endcomponent

@component('mail::button', ['url' => route('activity.index'), 'color' => 'blue'])
        Check out your activities.
@endcomponent

Sincerely
        
{{env('APP_NAME')}}
@endcomponent
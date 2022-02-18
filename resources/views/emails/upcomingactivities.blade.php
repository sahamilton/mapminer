@component('mail::message')

## Your Upcoming Activities

{{$user->person->firstname}} 

You have the following activities coming up in the next seven days.
@component('mail::table')
Company      	| Follow Up Date  |
| ------------- | --------:|
@foreach ($activities as $activity)
| <a href="{{route('address.show',$activity->relatesToAddress->id)}}">{{$activity->relatesToAddress->businessname}} </a>|  {{$activity->activity_date->format('D jS M')}} | 
@endforeach

@endcomponent

@component('mail::button', ['url' => route('activity.index'), 'color' => 'blue'])
        Check out your activities.
@endcomponent

You can click on the attached file to import these activities into Outlook.

Sincerely
        
{{env('APP_NAME')}}
@endcomponent

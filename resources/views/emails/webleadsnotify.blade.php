@component('mail::message')

## New Web Lead 

{{$team->firstname}}, 

You have been assigned a new lead that came through the people ready website.  The details are below:



Note that the nearest branch is

Sincerely
        
{{env('APP_NAME')}}
@endcomponent
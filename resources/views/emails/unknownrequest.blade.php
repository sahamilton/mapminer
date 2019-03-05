@component('mail::message')

Ahoy There!


{!!$content!!}



Thanks,<br>
{{ config('app.name') }}
@endcomponent

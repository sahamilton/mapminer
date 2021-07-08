@component('mail::message')
# File Transferred up


Just to confirm that the {{config('app.name')}} transferred {{$file}} at {{now()->format('Y-m-d h:i')}} to {{$path}}.


Thanks,<br>
{{ config('app.name') }}
@endcomponent

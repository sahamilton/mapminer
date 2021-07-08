@component('mail::message')
# File Transferred up


Just to confirm that the {{config('app.name')}} transferred at {{now()->format('Y-m-d h:i')}} to {{$backup}}.zip and added to Dropbox.


Thanks,<br>
{{ config('app.name') }}
@endcomponent

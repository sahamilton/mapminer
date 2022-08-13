@component('mail::message')
# Database Backed up


Just to confirm that the {{config('app.name')}} {{$type}} was backed up at {{now()->format('Y-m-d h:i')}} to {{$backup}}.zip and added to Dropbox.


Thanks,<br>
{{ config('app.name') }}
@endcomponent

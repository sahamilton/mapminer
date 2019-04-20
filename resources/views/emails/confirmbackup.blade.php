@component('mail::message')
# Database Backed up


Just to confirm that the {{config('app.name')}} database was backed up at {{now()->format('Y-m-d h:i')}} to {{$backup}}.zip;

{{ asset('storage/backups/'.$backup.'.zip') }}


Thanks,<br>
{{ config('app.name') }}
@endcomponent

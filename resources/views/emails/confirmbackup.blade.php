@component('mail::message')
# Database Backed up


Just to confirm that the {{config('app.name')}} database was backed up at {{now()->format('Y-m-d h:i')}} to {{$backup}}.zip;

@component('mail::button', ['url' => "{{ asset('storage/backups/'.$backup.'.zip') }}"])
You can download the db here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

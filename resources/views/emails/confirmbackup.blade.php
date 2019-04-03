@component('mail::message')
# Database Backed up

Just to confirm that the {{config('app.name')}} database was backed up at {{now()->format('Y-m-d hh:mm')}} to {{$backup}};

@component('mail::button', ['url' => ''])
You can download the db here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

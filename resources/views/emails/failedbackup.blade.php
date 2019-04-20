@component('mail::message')
# Failed Database Backed up


We were unable to backup {{config('app.name')}} database at {{now()->format('Y-m-d h:i')}} ;

@component('mail::button', ['url' => ''])
You can download the db here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

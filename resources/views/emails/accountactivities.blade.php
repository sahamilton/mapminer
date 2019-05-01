@component('mail::message')
# {{$company->companyname}} Activities

Attached is the cumulative report of {{$company->companyname}} activities for the period from {{$period['from']}} to {{$period['to']}}.

Please advise Sales Operations if you no longer need this report.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

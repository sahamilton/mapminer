@component('mail::message')
# {{$campaign->title}} Summary

Attached is the summary report of {{$campaign->title}} campaign.

Please advise Sales Operations if you no longer need this report.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# Weekly Open Top 25 Opportunities Report

Attached is your weekly report of open top 25 opportunities by branch for the week ended {{$period->format('jS M, Y')}}.

If you no longer need this report please notify Sales Operations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

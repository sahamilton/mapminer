@component('mail::message')
# Branch Opportunities Report

Attached is your weekly report of branch statistics for ended {{$period['to']->format('jS M, Y')}}.

If you no longer wish to receive this report please notify Sales Operations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

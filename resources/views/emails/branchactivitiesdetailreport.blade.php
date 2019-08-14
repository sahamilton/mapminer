@component('mail::message')
# Branch Activities Detail Report

Attached is your weekly report of branch statistics for the period from {{$period['from']->format('jS M, Y')}} to  {{$period['to']->format('jS M, Y')}}.

If you no longer wish to receive this report please notify Sales Operations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
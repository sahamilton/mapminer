@component('mail::message')
# Daily Branch Report

{{$person->firstname}}

Attached is your daily report of branch statistics for {{$period['from']->format('jS M, Y')}}.

If you no longer wish to receive this report please notify Sales Operations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

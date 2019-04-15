@component('mail::message')
# Sales Appointments vs Opportunitie Won


Attached is the weekly report of sales appointments by opportunities won for all branches for the week ended {{$period['to']->format('jS M, Y')}}.

If you no longer need this report please advise Sales Operations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

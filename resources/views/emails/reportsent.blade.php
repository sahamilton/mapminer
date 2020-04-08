@component('mail::message')
# {{$report->report}} 
 
{{$user->person->firstname}}

Attached is your {{strtolower($report->report)}} for 
@if ($period['to']->diffInDays($period['from']) > 0)
the period from {{$period['from']->format('jS M, Y')}} to {{$period['to']->format('jS M, Y')}}.
@else
{{$period['from']->format('jS M, Y')}}.
@endif

If you no longer wish to receive this report please notify Sales Operations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

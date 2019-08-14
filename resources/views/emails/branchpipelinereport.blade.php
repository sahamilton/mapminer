@component('mail::message')
# Branch Pipeline Report

Attached is your report of the pipeline of branch opportunities for the period from {{now()->format('M Y')}} to {{now()->addMonths(5)->format('jS M, Y')}}.

If you no longer wish to receive this report please notify Sales Operations.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

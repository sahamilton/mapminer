@component('mail::message')
# Opportunity Won

The {{$opportunity->branch->branch->branchname}} branch has won the {{$opportunity->title}} opportunity at {{$opportunity->address->address->businessname}}, {{$opportunity->address->address->city}} valued at ${{number_format($opportunity->value,2)}}.


Congratulations!<br>
{{ config('app.name') }}
@endcomponent

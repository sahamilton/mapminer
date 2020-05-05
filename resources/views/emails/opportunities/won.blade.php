@component('mail::message')
# Opportunity Won

The {{$opportunity->branch->branch->branchname}} branch has won the {{$opportunity->title}} opportunity at {{$opportunity->address->address->businessname}}, {{$opportunity->address->address->city}} valued at ${{number_format($opportunity->value,2)}}.

Branch Manager(s):
@foreach ($branchManager as $manager)

{{$manager['name']}} (
{{$manager['email']}})

@endforeach

Congratulations!

{{ config('app.name') }}
@endcomponent

@component('mail::message')
# New Large Opportunity Created

Branch {{$opportunity->branch->branch->branchname}} has created a new large opportunity at {{$opportunity->address->address->businessname}}, {{$opportunity->address->address->city}} valued at ${{number_format($opportunity->value,2)}}.  

The {{$opportunity->title}} is for {{$opportunity->requirements}} people for {{$opportunity->duration}} months and is described as  {{$opportunity->description}}.  This opportunity is expected to close on {{$opportunity->expected_close->format('F jS, Y')}}.

Branch Manager(s):
@foreach ($branchManager as $manager)

{{$manager['name']}} (<{{$manager['email']}}>)

@endforeach


{{ config('app.name') }}
@endcomponent

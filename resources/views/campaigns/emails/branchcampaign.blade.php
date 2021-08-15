@component('mail::message')
# Branch {{$data->branchname}} {{$campaign->title}} Initiatives
Hi @foreach ($data->manager as $manager) {{$manager->firstname}} @if(! $loop->last) / @endif @endforeach, 

Here is a quick glance at what you have coming up this week in the {{$campaign->title}} campaign:


@include('campaigns.emails.partials._branchdetails')

Thanks,<br>
{{ config('app.name') }}
@endcomponent

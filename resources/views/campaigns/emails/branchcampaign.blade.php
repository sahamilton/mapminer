@component('mail::message')
# Branch {{$data->branchname}} {{$campaign->title}} Initiatives
Hi {{$data->manager->first()->firstname}}, Here is a quick glance at what you have coming up this week in the {{$campaign->title}} campaign:


@include('campaigns.emails.partials._branchdetails')

Thanks,<br>
{{ config('app.name') }}
@endcomponent

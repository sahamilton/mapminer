@component('mail::message')
# Branch {{$branch->branchname}} {{$campaign->title}} Initiatives
Hi {{$branch->manager->first()->firstname}}, Here is a quick glance at what you have coming up this week:


@include('campaigns.emails.partials._branchdetails')

Thanks,<br>
{{ config('app.name') }}
@endcomponent

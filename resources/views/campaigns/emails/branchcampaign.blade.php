@component('mail::message')
# Branch {{$branch->branchname}} {{$campaign->title}} Initiatives
Hi {{$branch->manager->first()->firstname}}, Here is a quick glance at what you have coming up this week:
@php
$views = [
            'offered'=>"New Sales Initiative Leads", 
            'untouchedLeads'=>"Untouched Sales Initiatives Leads", 
            'opportunitiesClosingThisWeek'=>"Opportunities to Close this Week", 
             
        ];
@endphp

@include('campaigns.emails.partials._branchdetails')
# Upcoming Activities
@include('campaigns.emails.partials._allactivities')
Thanks,<br>
{{ config('app.name') }}
@endcomponent

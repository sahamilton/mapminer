@component('mail::message')
# Branch {{$branch->branchname}} {{$campaign->title}} Initiatives
@php
$views = [
            'offered'=>"Sales Initiative Leads", 
            'untouchedLeads'=>"Untouched Sales Initiatives Leads", 
            'leads'=>"Working Sales Initiatives Leads", 
            'openActivities'=>"Sales Initiatives Open Activities", 
            'opportunitiesClosingThisWeek'=>"Opportunities closing this week", 
        ];
@endphp

@include('campaigns.emails.partials._branchdetails')

Thanks,<br>
{{ config('app.name') }}
@endcomponent

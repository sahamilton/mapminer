@component('mail::message')
# Branch {{$branch->branchname}} {{$campaign->title}} Initiatives
Hi {{$branch->manager->first()->firstname}}, Here is a quick glance at what you have coming up this week:
@php
$views = [
            'offered'=>['title'=>"New Sales Initiative Leads", 'detail'=>''],
            'untouchedLeads'=>['title'=>"Untouched Sales Initiatives Leads", 'detail'=>'Here are the Sales Initiative Leads that you accepted but do not have any activity. Make sure you enter in any activity that has taken place to remove these Leads for the Untouched list.'],
            'opportunitiesClosingThisWeek'=>['title'=>"Opportunities to Close this Week", 'detail'=>'Make sure you are updating your Opportunities status. Opportunities should be marked Closed – Won once we have billed the our new customer.'],
            'upcomingActivities'=>['title'=>"Upcoming Activities", 'detail'=>''],
             
        ];
@endphp

@include('campaigns.emails.partials._branchdetails')

Thanks,<br>
{{ config('app.name') }}
@endcomponent

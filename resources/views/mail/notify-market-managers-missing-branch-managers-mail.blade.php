@component('mail::message')
# Activate Your Branch Managers

{{$manager->first_name}}

The following Branch Managers in your team, according to Oracle HRMS, have not been activated in Mapminer.  

@component('mail::table')
| Team Member       | Role         | Branch        |
| :--------- | :------------- | :------------- |
@foreach ($manager->teamMembers as $member)
| {{$member->FullName}} | {{$member->job_profile}} |{{$member->location_name}} |
@endforeach
@endcomponent

As their manager you can activate them yourself by logging into Mapminer, and navigating to your Profile and the My Team section or use this link:

@component('mail::button', ['url' => route('user.show', $manager->mapminerUser->id), 'color' => 'blue'])
       
Activate Your Team Members
@endcomponent

Alternatively you can contact <a href="mailto:support@tbmapminer.com">support@tbmapminer.com</a> and request which team members and branch you would like activated.

For full details on how to manage your team check out the [Mapminer training video]({{route('training.show', 15)}}).

Thanks,<br>
{{ config('app.name') }}
@endcomponent

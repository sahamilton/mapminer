@component('mail::message')
# Activate Your Branch Managers

{{$manager->first_name}}

The following Branch Managers in your team, according to Oracle HRMS, have not been activated in Mapminer.  

@foreach ($manager->teamMembers as $member)
- {{$member->FullName}} Branch {{$member->location_name}}
@endforeach


As their manager you can activate them yourself by logging into Mapminer, and navigating to your Profile and the My Team section or use this link:

@component('mail::button', ['url' => route('user.show', $manager->mapminerUser->id), 'color' => 'blue'])
       
Activate Your Team Members
@endcomponent

Alternatively you can contact support@tbmapminer.com and request which team members and branch you would like activated.

For full details on how to manage your team check out the [Mapminer training video]({{route('training.show', 15)}}).

Thanks,<br>
{{ config('app.name') }}
@endcomponent

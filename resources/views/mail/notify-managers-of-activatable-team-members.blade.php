@component('mail::message')
# Activate Your Team Members

{{$manager->mapminerUser->person->firstname}}

The following branch manager members of your team are not activated in Mapminer.  


@component('mail::table')
| Team Member       | Role         | Branch        |
| :--------- | :------------- | :------------- |
@foreach ($teamMembers as $team)
| {{$team->fullName()}} | {{$team->job_profile}} |{{$team->location_name}} |
@endforeach
@endcomponent

You can activate your team members through [your Mapminer profile]({{route('team.manage', $manager->mapminerUser->id)}}). 

@component('mail::button', ['url' => route('team.manage', $manager->mapminerUser->id)])
Activate your team members now!
@endcomponent

Check out the 'Managing your team' [Mapminer training video]({{route('training.show', 15)}}) .

If you need further help managing your team, or using Mapminer, please reach out to <a href="mailto:support@tbmapminer.com">Mapminer Support</a>


{{ config('app.name') }}
@endcomponent

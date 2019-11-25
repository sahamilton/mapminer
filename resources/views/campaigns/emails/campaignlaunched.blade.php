@component('mail::message')
# {{$campaign->title}} Launched

Hi {{$user->person->firstname}}

The {{$campaign->title}} has been launched.  
You can see the details at this link

@component('mail::button', ['url' => route('campaigns.show', $campaign->id)])
Campaign Details
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

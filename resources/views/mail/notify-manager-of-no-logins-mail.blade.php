@component('mail::message')
Hi {{$user->person->firstname}}:

Our records indicate that you have not accessed TBMapminer since {{$user->lastlogin->format('M jS, Y')}}.

We plan to remove your account from Mapminer no later than {{now()->addWeek(2)->format('M jS, Y')}}.  If you wish to retain your account please make sure that you [login](https://tbmapminer.com) at least once before {{now()->addWeek(2)->format('M jS, Y')}} at [TBMapminer.com](https://tbmapminer.com) or use this link.

@component('mail::button', ['url' => route('login'), 'color'=>'primary'])
     Login to Mapminer
@endcomponent

If you have any questions about Mapminer please contact <a href="mailto:support@tbmapminer.com">Mapminer Support</a>.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

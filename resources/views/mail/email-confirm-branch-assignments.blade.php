@component('mail::message')
# Please Confirm Your Branch Assignments

{{$person->firstname}}

In our on-going efforts to ensure that the data in Mapminer is correct, we would like you to confirm you current branch assignments.  Mapminer shows that you have the following branches reporting to you.  

@foreach ($branches as $branch)
- {{$branch}}
@endforeach

Please use this link to confirm or update your branch assignements (note you should login to Mapminer before using this link)

@component('mail::button', ['url' => route('branchassociation.confirm',[$token]), 'color' => 'blue'])
        Update my branch associations.
@endcomponent

Note this is a one-time link that will expire on {{$expiration->format('M d, Y')}} at {{$expiration->format('h:m a')}}.

Alternatively you can simply reply to this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

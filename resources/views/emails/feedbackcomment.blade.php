@component('mail::message')
# Comment Added

{{$feedback->providedBy->person->firstname}}

Related to your original feedback:

@component('mail::panel')

{{$feedback->feedback}}

@endcomponent

{{$user->person->fullName()}} has added a comment:

@component('mail::panel')

{{$feedback->comments->last()['comment']}}

@endcomponent

@if($feedback->status == 'closed')

This feedback is now closed.

@else
@component('mail::button', ['url' => route('feedback.show',$feedback->id),'color'=>'blue'])

See Feedback thread here
@endcomponent
or use this link {{route('feedback.show',$feedback->id)}}
@endif

Thanks

{{ config('app.name') }}
@endcomponent

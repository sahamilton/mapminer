@component('mail::message')

## Please Confirm Your Branch Associations 

{{$person->firstname}}, 

{!! $message !!}

@if($person->branchesServiced()->exists()) 
Please review your current branch associations that are in Mapminer.

@component('mail::table')
| Branch Id     | Branch Name  |
| ------------- | ------------- |
@foreach ($person->branchesServiced as $branch)
| {{$branch->id}} | {{$branch->branchname}} |
@endforeach
@endcomponent

@else

Our records show that you currently are not associated with any branches. 

@endif

If this is incorrect please use the button below to update your branch associations. 

@component('mail::button', ['url' => route('branchassociation.confirm',[$token,'cid'=>$cid]), 'color' => 'blue'])
        Update my branch associations.
@endcomponent

If they are all correct please use this link to let us know.

@component('mail::button', ['url' => route('branchassociation.correct',[$token,'cid'=>$cid]), 'color' => 'green'])
        All Correct.
@endcomponent

Note that these links will expire on {{$expiration->format('l jS M Y')}} at {{$expiration->format('g:i a')}}

Sincerely

Sales Operations    

{{env('APP_NAME')}}
@endcomponent
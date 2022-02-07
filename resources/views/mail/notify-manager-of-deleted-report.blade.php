@component('mail::message')
# Team Member Deleted

{{$manager->firstname}}

This is to notify you that {{$user->fullName()}} has been deleted from Mapminer.  If you believe this is in error please notify <a href="mailto:{{ Config::get('mapminer.system_support') }}?subject=Deleting {{$user->fullName()}}">Sales Operations</a> immediately.

@if(count($branches) >0)
Note that {{$user->fullName()}} was assigned to the following branches.

@foreach ($branches as $branch)
* {{$branch}}
@endforeach

You will need to notify <a href="mailto:{{ Config::get('mapminer.system_support') }}?subject=Reassigning {{$user->fullName()}}'s branches">Sales Operations</a> who is managing these branches now.
@endif


Thanks,<br>
{{ config('app.name') }}
@endcomponent

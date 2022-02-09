@component('mail::message')
# Team Member Added

{{$manager->firstname}}

This is to notify you that {{$user->fullName()}} now has access to Mapminer.

@if(count($branches) >0)
Note that {{$user->fullName()}} was assigned to the following branches.

@foreach ($branches as $branch)
* {{$branch}}
@endforeach

@endif
If you believe this is in error please notify 
<a href="mailto:{{ Config::get('mapminer.system_support') }}?subject=Adding {{$user->fullName()}}">Sales Operations</a> immediately.


Thanks,<br>
{{ config('app.name') }}
@endcomponent

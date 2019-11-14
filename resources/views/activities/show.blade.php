@extends('site.layouts.default')
@section('content')
<div class="container">
<h4>Activity at {{$activity->relatesToAddress->businessname}}</h4>
<p><a href="{{route('address.show', $activity->relatesTOAddress->id)}}">Return to address</a></p>
<p><strong>Recorded by:</strong>
    @if($activity->user)
    {{$activity->user->person->fullName()}}
    @else
    No longer a Mapminer User
    @endif
</p>
<p><strong>Branch:</strong>
    @if($activity->branch)
    {{$activity->branch->branchname}}
    @else
    No branch recorded
    @endif
</p>
<p><strong>Date:</strong>
    {{$activity->activity_date->format('Y-m-d')}}</p>
<p><strong>Activity:</strong>
    {{$activity->type->activity}}</p>
<p><strong>Details:</strong>
    {{$activity->note}}</p>

</div>

@endsection
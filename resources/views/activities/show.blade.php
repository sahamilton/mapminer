@extends('site.layouts.default')
@section('content')
<div class="container">
<h4>Activity at {{$activity->relatesToAddress->businessname}}</h4>
<p><a href="{{route('address.show', $activity->relatesToAddress->id)}}">Return to address</a></p>
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
@if($activity->relatedContact)
    <p><strong>Contact:</strong>
        @foreach ($activity->relatedContact as $contact)
           <li> {{$contact->fullName()}} {{$contact->id}}</li>
        @endforeach

@endif

<p><strong>Status:</strong>{{$activity->completed == 1 ? 'Completed' : 'Open'}}</p>
</div>
@endsection

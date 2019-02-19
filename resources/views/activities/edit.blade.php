@extends('site.layouts.default')
@section('content')
<h2>Edit Activity</h2>
<form name="editactivity" action="{{route('activity.update',$activity->id)}}" method="post">
	@csrf
	@method('put')
	@include('activities.partials._activityform')
	<input hidden name="location_id" value="{{$activity->address_id}}" />
	<input type="submit" name="submit" class="btn btn-success" value="Edit Activity" />
</form>
@include('partials._scripts')
@endsection
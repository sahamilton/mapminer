@extends('admin.layouts.default')
@section('content')
<h2>Edit Activity Type</h2>
<form name="editactivitytype" method="post" action = "{{route('activitytype.update',$activitytype->id)}}">
	@csrf
	@method('put')
	@include('activitytypes.partials._activitytypeform')
	<input type="submit" name="submit" class="btn btn-info" value="Edit Activity Type" />
</form>
@endsection
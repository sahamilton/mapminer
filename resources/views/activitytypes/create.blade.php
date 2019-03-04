@extends('admin.layouts.default')
@section('content')
<h2>Create New Activity Type</h2>
<form name="createactivitytype" method="post" action = "{{route('activitytype.store')}}">
	@csrf
	@include('activitytypes.partials._activitytypeform')
	<input type="submit" name="submit" class="btn btn-info" value="Create New Activity Type" />
</form>
@endsection
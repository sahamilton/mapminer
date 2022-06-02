@extends('admin.layouts.default')
@section('content')
<h2>Edit Activity Type</h2>
<form name="editactivitytype" method="post" action = "{{route('activitytype.update',$activitytype->slug)}}">
	@csrf
	@method('put')
	@bind($activitytype)
	==>{{$activitytype->slug}}
	@include('activitytypes.partials._activitytypeform')
	@endbind
	<input type="submit" name="submit" class="btn btn-info" value="Edit Activity Type" />
</form>
@endsection
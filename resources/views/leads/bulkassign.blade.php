@extends('admin.layouts.default')
@section('content')

	<h2>Assign {{$leadsource->sourcename}} Prospect Geographically</h2>
	<p><a href="{{route('leadsource.index')}}">Return to all prospect sources</a></p>
	<form name="bulkassign" method="post" action="{{route('leads.geoassign', $leadsource->id)}}" >
		@csrf()
    <!-- assign based on industry -->

    <!-- roles to assign to -->
   
    	<select name="roles[]" multiple class="form-control" >
    		@foreach ($leadroles as $key=>$role)
    			<option value="{{$role}}">{{$role}}</option>
    		@endforeach
    	</select>
	    <input type="submit" name="submit" value="Assign Geographically" class="btn btn-info">
	</form>


@include('partials._scripts')
@endsection
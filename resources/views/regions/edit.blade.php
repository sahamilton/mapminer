@extends('admin.layouts.default')
@section('content')
<h2>Edit {{$region->region}} Region</h2>
<form method="post" action="{{route('region.update',$region->id)}}" name="editregion" >
	@csrf
	@method('put')
	@include('regions.partials._form')
	<input type="submit" class="btn btn-info" value="Update Region" />
</form>


@endsection
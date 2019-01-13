@extends('site.layouts.default')
@section('content')
<h2>Edit Location</h2>
<form name="editlocation" action="{{route('address.update',$address->id)}}" method="post">
	@csrf
	@method('put')
	@include('addresses.partials._form')
	<input type="submit" name="submit" class="btn btn-success" value="Edit Location" />
</form>
@endsection
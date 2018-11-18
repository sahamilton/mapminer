@extends('site.layouts.default')
@section('content')
<h2>Update {{$mylead->businessname}} Lead</h2>
<form name="createlead" action="{{route('myleads.update',$mylead)}}" method="post">
	@csrf
	@method('put')
	@include('myleads.partials._form')
	<input type="submit" name="submit" class="btn btn-success" value="Update Lead" />
</form>
@endsection
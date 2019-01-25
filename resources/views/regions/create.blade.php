@extends('admin.layouts.default')
@section('content')
<h2>Create New Region</h2>
<form method="post" action="{{route('region.store')}}" name="createregion" >
	@csrf

	@include('regions.partials._form')
	<input type="submit" class="btn btn-info" value="Create Region" />
</form>


@endsection
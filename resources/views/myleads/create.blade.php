@extends('site.layouts.default')
@section('content')
<h2>Create New Lead</h2>
<form name="createlead" action="{{route('myleads.store')}}" method="post">
	@csrf
	@include('myleads.partials._form')
	<input type="submit" name="submit" class="btn btn-success" value="Create Lead" />
</form>
@endsection
@extends('admin/layouts/default')
@section('content')
<h1>Edit Filter</h1>
	
<form
name="searchfilters"
method="post"
action="{{route('searchfilters.update',$filter->id}}">
@csrf
@method="patch"

@include('filters.partials._filterform')
<input type="submit" class="btn btn-primary">
</form>
@include('partials/_scripts')
@endsection

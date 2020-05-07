@extends('admin/layouts/default')
@section('content')
<h1>Add New Filter</h1>
	@if (count($errors)>0)
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
    @endif
<form 
name="searchfilters"
method="post"
action="searchfitlers.store">
@csrf


@include('filters.partials._filterform')
<input type="submit"
class="btn btn-primary" />
</form>
@include('partials/_scripts')
@endsection

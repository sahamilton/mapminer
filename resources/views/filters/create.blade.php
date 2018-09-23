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

{{Form::open(array('url'=>'/admin/searchfilters'))}}
@include('filters.partials._filterform')
{{Form::submit('Create Filter',array('class'=>'btn btn-primary'))}}
@include('partials/_scripts')
@endsection
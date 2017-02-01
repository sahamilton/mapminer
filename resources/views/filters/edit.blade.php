@extends('site/layouts/default')
@section('content')
<h1>Edit Filter</h1>
	@if ($errors->has())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>        
            @endforeach
        </div>
    @endif

{{Form::open(array('route'=>['admin.searchfilters.update',$filter->id], 'method' => 'PATCH'))}}
@include('filters.partials._filterform')
{{Form::submit('Edit Filter',array('class'=>'btn btn-primary'))}}
@include('partials/_scripts')
@stop
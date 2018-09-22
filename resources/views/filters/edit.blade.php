@extends('admin/layouts/default')
@section('content')
<h1>Edit Filter</h1>
	

{{Form::open(array('route'=>['searchfilters.update',$filter->id], 'method' => 'PATCH'))}}
@include('filters.partials._filterform')
{{Form::submit('Edit Filter',array('class'=>'btn btn-primary'))}}
@include('partials/_scripts')
@stop
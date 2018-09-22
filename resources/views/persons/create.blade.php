@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Create a Manager ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		Create a New Manager

		
	</h3>
</div>

<!-- Tabs -->


<form class="form-horizontal" method="post" action="{{ route('person.store') }}" autocomplete="off">
<?php $buttonLabel ='Create Person';?>
{{Form::open(['route'=>'person.store'])}}
@include('persons/partials/_form')
{{Form::close()}}

@stop
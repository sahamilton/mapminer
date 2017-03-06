@extends('site/layouts/default')

{{-- Page title --}}
@section('title')
Edit Branch ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		Edit Branch

		<div class="pull-right">
			<a href="{{ route('branches.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
		</div>
	</h3>
</div>
<?php 
	
    foreach ($branch->servicelines as $serving){
		$served[] = $serving->id;
	}
$buttonLabel = 'Edit Branch';?>
{{Form::model($branch, ['method'=>'PATCH','route'=>['branches.update', $branch->id]]) }}
	@include('branches/partials/_form')
{{Form::close()}}

@stop
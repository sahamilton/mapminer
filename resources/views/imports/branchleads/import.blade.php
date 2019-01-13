@extends('admin.layouts.default')
@section('content')

<div class="container">


	<?php $actions = ['Replace','Add / Edit'];?>


	<div class="page-header">
		<h2>Import Branch Leads</h2>

	</div>

	<h3>Steps to import branch leads</h3>
	<ol>
		<li>First create your csv file of branches from the <a href="" title = "Download Branch Leads Import template">template.</a>  Your import file must contain at least these fields:
			<ol>
			@foreach ($requiredFields as $field)
				<li style="color:red">{{$field}}</li>
			@endforeach
		</ol>
		</li>
		<li>Save the CSV file locally on your computer.</li>
		
		<li>To add / update/ delete a single branch use the <a href="{{route('branches.index')}}">regular branch listing</a> and the green actions button</li></ul>

		<li>Select the file and import</li>

	</ol>
	<div>
		<form method='post' action ="{{route('branchleads.import')}}" enctype="multipart/form-data" >
		@csrf
		<!-- File Location -->
		<div class="form-group{{ $errors->has('upload') ? ' has-error' : '' }}">
			<label class="col-md-2 control-label" for="field" >Upload File Location</label>
			<div class="input-group input-group-lg ">
				<input type="file" 
				class="form-control" 
				name='upload' id='upload' 
				description="upload" 
				value="{{  old('upload')}}">
				<span class="help-block">
					<strong>{{ $errors->has('upload') ? $errors->first('upload') : ''}}</strong>
				</span>
			</div>

		</div>
		
		<input type="submit" class="btn btn-success" value="Import Branches" />

		<input type="hidden" name="additionaldata[]" @if(isset($data)) value = "{{$data['additionaldata']}} @endif" >
		</form>

</div>


@include('partials/_scripts')
@endsection


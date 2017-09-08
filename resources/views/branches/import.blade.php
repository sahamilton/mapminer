@extends('admin.layouts.default')
@section('content')

<div class="container">


	<?php $actions = ['Replace','Add / Edit'];?>

	{{-- Content --}}
	@section('content')
	<div class="page-header">
		<h3>Import Branches</h3>

	</div>

	<h2>Steps to import branches</h2>
	<ol>
		<li>First create your csv file of branches from the template.  Do not change, add or delete any field / column</li>
		<li>Save the CSV file locally on your computer.</li>
		<li>Determine if you want to completely erase and reimport the list or just make adds & edit from the import list</li>
		<ul><li>Purge will delete all branches and reimport from theh spreadsheet</li>
		<li>Edits will delete the branch based on branch muber and reimport all the data for that one branch</li>
		<li>To delete a single branch use the regular branch listing and the green actions button</li></ul>

		<li>Select the file and import</li>

	</ol>
	<div>
		<form method='post' action ="{{route('branches.import')}}" enctype="multipart/form-data" >
		{{csrf_field()}}

		<!-- Service Lines -->
		<div class="form-group{{ $errors->has('serviceline)') ? ' has-error' : '' }}">
			<label class="col-md-2 control-label">Servicelines:</label>
			<div class="input-group input-group-lg ">

				<select name="serviceline[]'" multiple >
					@foreach ($servicelines as $key=>$serviceline)
					<option value="{{$key}}">{{$serviceline}}</option>
					@endforeach
				</select>
				<span class="help-block">
					<strong>{{ $errors->has('serviceline') ? $errors->first('serviceline') : ''}}
					</strong>
				</span>
			</div>
		</div>
		<!-- ./ servicelines -->

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
		<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
			<label class="col-md-2 control-label" for="type" >Import Type</label>
			<div class="input-group input-group-lg ">
				@foreach ($actions as $action)
				<input type ='radio' name ="importtype" value='{{$action}}'/>{{ $action}}
				@endforeach
			</div>
		</div>
		<input type="submit" class="btn btn-success" value="Import Branches" />

		</form>

</div>
@stop	

@include('partials/_scripts')
@stop

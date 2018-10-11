@extends('admin.layouts.default')
@section('content')

<div class="container">

	<div class="page-header">
		<h3>Import Branches Team</h3>

	</div>

	<h2>Steps to import branch teams</h2>
	<ol>
		<li>First create your csv file of branch teams from the template.  Do not change, add or delete any field / column</li>
		<li>Save the CSV file locally on your computer.</li>

		<li>Select the file and import</li>

	</ol>
	<div>
		<form method='post' action ="{{route('branches.teamimport')}}" enctype="multipart/form-data" >
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
		
		<input type="submit" class="btn btn-success" value="Import Branch Teams" />

		<input type="hidden" name="additionaldata[]" @if(isset($data)) value = "{{$data['additionaldata']}} @endif"
		</form>

</div>
	

@include('partials/_scripts')
@endsection


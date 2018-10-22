@extends('admin.layouts.default')

@if($errors->any())
<h4>{{$errors->first()}}</h4>
@endif
{{-- Content --}}
@section('content')
<div class="page-header">

	<h3>Import Users</h3>

</div>
<div class="container" style="margin-bottom:80px">
<h2>Steps to import users</h2>
<ol>
	<li>First create your csv file of projects from the template.  Your import file must contain at least {{count($requiredFields)}} columns that can be mapped to:
	<ol>
		@foreach ($requiredFields as $field)
		<li style="color:red">{{$field}}</li>
		@endforeach
	</ol>
	</li>
	<li>Save the CSV file locally on your computer.</li>
	<li>Select the file and import</li>
	<li>Notify the new users that they should use the 'Forgot Password' link to set their password.  They will recieve an email with a link that includes a unique token.</li>
</ol>

{{ Form::open(array('route'=>'admin.users.bulkimport', 'files' => true)) }}
	<div class="form-group @if ($errors->has('upload')) has-error @endif">
		{{Form::file('upload')}}
		
		<span class="help-block{{ $errors->has('serviceline') ? ' has-error' : '' }}">
			<strong>{{$errors->has('upload') ? $errors->first('uploda')  : ''}}</strong>
		</span>
	</div>

	<!-- Service Lines -->
	@include('servicelines.partials._selector')
	<div class="row">
		<input class="btn btn-xs btn-success" type="submit" name="submit" value="Import Users" />
	<!-- ./ servicelines -->
	</div>
</form>

    </div>
@include('partials/_scripts')
@endsection

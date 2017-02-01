@extends('admin.layouts.default')

@if($errors->any())
<h4>{{$errors->first()}}</h4>
@endif
{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>
			Import Users</h3>

			
	</div>

<h2>Steps to import users</h2>
<ol>
<li>First create your csv file of users from the template.  Do not change, add or delete any field / column</li>
<li>Save the CSV file locally on your computer.</li>
<li>Select the file and import</li>
<li>Notify the new users that they should use the 'Forgot Password' link to set their password.  They will recieve an email with a link that includes a unique token.</li>
</ol>

{{ Form::open(array('route'=>'admin.users.bulkimport', 'files' => true)) }}


<div>


<div>
{{Form::file('upload')}}
{{ $errors->first('upload') }}
</div></div>
<div>
<!-- Service Lines -->
				<div class="form-group @if ($errors->has('serviceline')) has-error @endif">
					{{Form::label('ServiceLine','Service Lines:', array('class'=>"col-md-2 control-label"))}}

<div class="col-md-6">
					{{Form::select('serviceline[]',$servicelines,isset($user) ? $user->serviceline->pluck('id') :'',array('class'=>'form-control','multiple'=>true))}}

					@if ($errors->has('serviceline')) <p class="help-block">{{ $errors->first('serviceline') }}</p> @endif
					</div></div>
				<!-- ./ servicelines -->
{{Form::submit('Import Users',['class' => 'btn btn-sm btn-success'])}}
</div>
{{Form::close()}}
@stop	
    
@include('partials/_scripts')
@stop
